<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use App\Services\PaymentMethodService;
use App\Traits\Auditable;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PaymentMethodApprovalController extends Controller
{
    use Auditable;
    public $layoutFolder = "admin.payment_setting.payment_method_review";
    public $paymentMethodService;
    public function __construct()
    {
        $this->paymentMethodService = new PaymentMethodService();
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('payment_method_review_list'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = PaymentMethod::where('is_sent_for_review', 1);

        if($request->filled('name')){
            $query->where('name', 'LIKE', '%' .$request->name . '%');
        }
        if($request->filled('payment_method_form_type')){
            $query->where('payment_method_form_type', $request->payment_method_form_type);
        }
        if($request->filled('status')){
            $query->where('status', $request->status);
        }
        if($request->filled('country_category')){
            $query->where('country_category', $request->country_category);
        }
        if ($request->filled('date_to') && $request->filled('date_from')) {

            $date = setStartDateEndDate($request->date_from, $request->date_to, 'Y-m-d');

            $query->whereDate('created_at', '>=', $date['from'])->whereDate('created_at', '<=', $date['to']);
        }

        $query =   $query->orderBy('id', 'desc');
        $payment_methods = ($request->pagination ? $query->paginate($request->pagination) : $query->paginate(20));
        $paymentCountryCategory = $this->paymentMethodService->paymentCountryCategory();
        $paymentMethodStatus = $this->paymentMethodService->paymentMethodStatus();
        return view("{$this->layoutFolder}.index", compact('payment_methods','paymentCountryCategory','paymentMethodStatus'));
    }


    public function edit(PaymentMethod $payment_method)
    {
        abort_if(Gate::denies('payment_method_review'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $paymentMethodApprovalStatus = $this->paymentMethodService->paymentMethodApprovalStatus();
        return view("{$this->layoutFolder}.edit", compact('payment_method','paymentMethodApprovalStatus'));
    }

    public function update(Request $request, PaymentMethod $payment_method)
    {
        abort_if(Gate::denies('payment_method_review'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        try{
          if($request->is_sent_for_review == 1){
              $payment_method->is_sent_for_review = 0;
              $payment_method->status = 1;

          }else{
              $payment_method->is_sent_for_review = 2;
          }
            $payment_method->save();

            self::auditLogEntry("PaymentMethodReview:updated", $payment_method->id,"App\Models\PaymentMethod", $payment_method );
            return redirect()->route('admin.payment-method-review.index')
                ->with('success', 'Payment Method updated successfully.');
        }catch (\Exception $exception){

            Log::error("PaymentMethodController::reviewStatusUpdate()" . $exception);
        }
        return redirect()->back()
            ->withErrors(['message', 'Updating Failed!']);
    }

    public function show(PaymentMethod $payment_method)
    {
        try{
            return view("{$this->layoutFolder}.show", compact('payment_method'));
        }catch (\Exception $exception){

            Log::error("PaymentMethodApprovalController::show()" . $exception);

        }
        return redirect()->route('admin.payment-method.index')
            ->with('error', 'Failed to delete!');

    }
}


