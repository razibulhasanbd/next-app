<?php

namespace App\Services;

use App\Http\Resources\Admin\PaymentMethodsResource;
use App\Models\AuditLog;
use App\Models\Country;
use App\Models\PaymentMethod;
use App\Traits\Auditable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentMethodService
{

    public function getCountryCodeByIpTrace($ipAddress)
    {
        try {
            $ipWhoDomain =  config('common.ip_check_domain.url');
            $response = Http::get($ipWhoDomain . $ipAddress . '?fields=country,country_code');
            $data = $response->json();
            return $data['country_code'] ?? null;
        }catch (\Exception $exception){
            Log::error("PaymentMethodService::getCountryByIpTrace()" . $exception);
            return null;
        }

    }

    private function countryCategory($shortCode)
    {
        return Country::select('country_category','name')
            ->where('short_name', $shortCode)
            ->first();
    }

    public function paymentList($countryCode)
    {
        $country = $this->countryCategory($countryCode);
        $countryCategory = $country->country_category ?? 0; // default NON-OFAC
        $paymentMethods = PaymentMethod::where('country_category', $countryCategory)
            ->whereStatus(1)
            ->orderBy('serial_number','ASC')
            ->get();

        $data = new PaymentMethodsResource($paymentMethods);
        return [
            'country' => $country->name ?? '',
            'country_category' => $this->countryCategoryTag($countryCategory),
            'payment_methods' => $data,
        ];
    }


    private function countryCategoryTag($countryCategory)
    {
        $categories = [
          '0' => 'NON-OFAC',
          '1' => 'OFAC',
        ];
        return $categories[$countryCategory] ?? '';
    }
    public function createPaymentMethod($request)
    {

        try{
            $paymentMethodData = [
                'name' => $request->name,
                'country_category' => $request->country_category,
                'payment_method' => $this->getPaymentMethod($request),
                'payment_method_form_type' => $request->payment_method_form_type,
                'commission' => $request->commission,
                'remarks' => $request->remarks,
                'status' => $request->status,
                'icon' => null,
                'data' => null,
                'qr_code_instructions' => null,
                'address' => null,
            ];

            if ($request->hasFile('icon')) {
                $fileName = $request->file('icon')->hashName();
                $path = Storage::disk('payment_methods')->putFileAs('payment_methods',$request->file('icon'),$fileName, ['visibility' => 'public']);
                $paymentMethodData['icon'] = env('DO_URL').$path;
            }

            if ($request->payment_method_form_type === 'bank_transfer') {
                $paymentMethodData['data'] = json_encode( [
                    'account_number' => $request->input('account_number'),
                    'routing_number' => $request->input('routing_number'),
                    'account_type' => $request->input('account_type'),
                    'iban' => $request->input('iban'),
                    'swift_code' => $request->input('swift_code'),
                    'bank_name' => $request->input('bank_name'),
                    'beneficiary_name' => $request->input('beneficiary_name'),
                    'beneficiary_address' => $request->input('beneficiary_address'),
                    'beneficiary_email' => $request->input('beneficiary_email'),
                ]);
            } else {
                $paymentMethodData['qr_code_instructions'] = $request->qr_code_instructions ? json_encode(array_filter($request->qr_code_instructions)) : null;
                $paymentMethodData['address'] = $request->address;
            }
            $paymentMethodData['created_by'] = Auth::id();
            $paymentMethodData['is_sent_for_review'] = 1;
            $paymentMethodData['status'] = 0;
            $paymentMethodData['serial_number'] = $this->getSerialNumber($request->country_category);
            return PaymentMethod::create($paymentMethodData);

        }catch (\Exception $exception){
            Log::error("PaymentMethodService::createPaymentMethod()" . $exception);
            throw new \Exception("Failed to create payment method");
        }

    }

    public function updatePaymentMethod($request, $payment_method)
    {
        try{
            $paymentMethodData = [
                'name' => $request->name,
                'country_category' => $request->country_category,
                'payment_method' => $this->getPaymentMethod($request),
                'payment_method_form_type' => $request->payment_method_form_type,
                'commission' => $request->commission,
                'remarks' => $request->remarks,
            ];

            if ($request->hasFile('icon')) {
                $fileName = $request->file('icon')->hashName();
                $path = Storage::disk('payment_methods')->putFileAs('payment_methods',$request->file('icon'),$fileName, ['visibility' => 'public']);
                $paymentMethodData['icon'] = env('DO_URL').$path;
            }

            if ($request->payment_method_form_type === 'bank_transfer') {
                $paymentMethodData['data'] = json_encode( [
                    'account_number' => $request->input('account_number'),
                    'routing_number' => $request->input('routing_number'),
                    'account_type' => $request->input('account_type'),
                    'iban' => $request->input('iban'),
                    'swift_code' => $request->input('swift_code'),
                    'bank_name' => $request->input('bank_name'),
                    'beneficiary_name' => $request->input('beneficiary_name'),
                    'beneficiary_address' => $request->input('beneficiary_address'),
                    'beneficiary_email' => $request->input('beneficiary_email'),
                ]);
                $paymentMethodData['address'] = null;
            } else {
                $paymentMethodData['qr_code_instructions'] = $request->qr_code_instructions ? json_encode(array_filter($request->qr_code_instructions)) : null;
                $paymentMethodData['address'] = $request->address;
                $paymentMethodData['data'] = null;
            }

            $paymentMethodData['updated_by'] = Auth::id();
            if($request->filled('status') && $request->status == 0){
                $paymentMethodData['is_sent_for_review'] = 3;

            }else{
                $paymentMethodData['is_sent_for_review'] = 1;

            }
            $paymentMethodData['status'] = 0;


            $payment_method->update($paymentMethodData);
            return $paymentMethodData;

        }catch (\Exception $exception){
            Log::error("PaymentMethodService::updatePaymentMethod()" . $exception);
            throw new \Exception("Failed to create payment method");
        }
    }
    /**
     * Returns an array of payment method statuses with their respective names.
     *
     * @return array An associative array where the keys are payment method status codes and the values are payment method status names.
     */
    public function paymentMethodStatus(): array
    {
        return [
            '1' => "Active",
            '0' => "Inactive",
        ];
    }
    /**
     * Returns an array of payment method approval statuses with their respective names.
     *
     * @return array An associative array where the keys are payment method approval status codes and the values are payment method approval status names.
     */
    public function paymentMethodApprovalStatus(): array
    {
        return [
            '1' => "Approved",
            '0' => "Rejected",
        ];
    }
    /**
     * Returns an array of payment country categories with their respective names.
     *
     * @return array An associative array where the keys are payment country category codes and the values are payment country category names.
     */
    function paymentCountryCategory(): array
    {
        return [
            '0' => "NON-OFAC",
            '1' => "OFAC",
        ];
    }

    public function getPaymentMethod($request)
    {
        if($request->payment_method_form_type == 'bank_transfer'){
            $paymentMethod = 'bank-transfer';
        }else if($request->payment_method_form_type == 'perfect_money'){
            $paymentMethod = 'perfect-money';
        }else{
            $paymentMethod  = Str::slug($request->name);
        }
        return $paymentMethod;
    }

    public function getSerialNumber($countryCategory = 0) : int
    {
        return PaymentMethod::where('country_category', $countryCategory)->max('serial_number') ?? 1;
    }
}
