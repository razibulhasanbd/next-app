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
use Exception;
use DB;

class PaymentMethodController extends Controller
{
    use Auditable;
    public $layoutFolder = "admin.payment_setting.payment_method";
    public $paymentMethodService;
    public function __construct()
    {
        $this->paymentMethodService = new PaymentMethodService();
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('payment_method_list'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = PaymentMethod::query();

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

        $paymentMethodStatus = $this->paymentMethodService->paymentMethodStatus();
        $paymentCountryCategory = $this->paymentMethodService->paymentCountryCategory();
        return view("{$this->layoutFolder}.index", compact('payment_methods',
            'paymentMethodStatus','paymentCountryCategory'));
    }

    public function create()
    {

        abort_if(Gate::denies('payment_method_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $paymentCountryCategory = $this->paymentMethodService->paymentCountryCategory();
        return view("{$this->layoutFolder}.create",compact('paymentCountryCategory'));
    }

    public function store(StorePaymentMethodRequest $request)
    {

        abort_if(Gate::denies('payment_method_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if(PaymentMethod::where('country_category', $request->country_category)
            ->where('name', $request->name)->exists()){
            return redirect()->back()->withInput()
                ->withErrors(['message' => 'Payment Method Already Exists']);
        }
        try{
            $model = $this->paymentMethodService->createPaymentMethod($request);
            self::auditLogEntry("PaymentMethod:created", $model->id,"App\Models\PaymentMethod", $model );

            return redirect()->route('admin.payment-method.index')
                ->with('success', 'Payment Method created successfully.');
        }catch (\Exception $exception){

            Log::error("PaymentMethodController::store()" . $exception);
        }
        return redirect()->route('admin.payment-method.index')
            ->withErrors(['message', 'Failed!']);

    }

    public function edit(PaymentMethod $payment_method)
    {
        $paymentCountryCategory = $this->paymentMethodService->paymentCountryCategory();
        $paymentMethodStatus = $this->paymentMethodService->paymentMethodStatus();
        return view("{$this->layoutFolder}.edit", compact(
            'payment_method','paymentMethodStatus','paymentCountryCategory'));
    }

    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $payment_method)
    {

        abort_if(Gate::denies('payment_method_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if(PaymentMethod::where('country_category', $request->country_category)
            ->where('payment_method_form_type', $request->payment_method_form_type)
            ->where('name', $request->name)
            ->where('id', '!=', $payment_method->id)->exists()){
            return redirect()->back()->withInput()
                ->withErrors(['message' => 'Payment Method Already Exists']);
        }
        try{
            $model = $this->paymentMethodService->updatePaymentMethod($request, $payment_method);
            $model = array_merge($model, [ 'id' => $payment_method->id]);
            self::auditLogEntry("PaymentMethod:updated", $payment_method->id,"App\Models\PaymentMethod", $model );
            return redirect()->route('admin.payment-method.index')
                ->with('success', 'Payment Method updated successfully.');
        }catch (\Exception $exception){

            Log::error("PaymentMethodController::update()" . $exception);
        }
        return redirect()->back()
            ->withErrors(['message', 'Updating Failed!']);
    }

    public function destroy(PaymentMethod $payment_method)
    {
        abort_if(Gate::denies('payment_method_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try{
            $payment_method->delete();
            self::auditLogEntry("PaymentMethod:deleted", $payment_method->id,"App\Models\PaymentMethod", $payment_method );
            return redirect()->route('admin.payment-method.index')
                ->with('success', 'Payment Method deleted successfully');

        }catch (\Exception $exception){

            Log::error("PaymentMethodController::store()" . $exception);

        }
        return redirect()->route('admin.payment-method.index')
            ->with('error', 'Failed to delete!');

    }
    public function show(PaymentMethod $payment_method)
    {
        abort_if(Gate::denies('payment_method_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try{
            return view("{$this->layoutFolder}.show", compact('payment_method'));
        }catch (\Exception $exception){

            Log::error("PaymentMethodController::show()" . $exception);

        }
        return redirect()->route('admin.payment-method.index')
            ->with('error', 'Something went wrong!');

    }

    public function paymentMethodOrder(Request $request){

        abort_if(Gate::denies('payment_method_order'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {

            $query = PaymentMethod::query();
            if($request->filled('country_category')){
                $query->where('country_category', $request->country_category);
            }else{
                $query->where('country_category',0); // by default if not selected
            }

            $payment_methods = $query->orderBy('serial_number', 'asc')->get();

            //dd($payment_methods->pluck('id'));
            $paymentCountryCategory = $this->paymentMethodService->paymentCountryCategory();
            return view("{$this->layoutFolder}.order", compact('payment_methods','paymentCountryCategory'));

        }catch (Exception $exception){
            Log::error("PaymentMethodController::order()" . $exception);
            return redirect()->route('admin.payment-method.index')
                ->with('error', 'Something went wrong!');
        }

    }
    public function paymentMethodOrderUpdate(Request $request){
        abort_if(Gate::denies('payment_method_order_update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        //dd($request->all());
        try{
            DB::beginTransaction();
            $ids = $request->ids;

            foreach ($ids as $key => $value) {
                $question = PaymentMethod::findOrFail($value);
                $question->serial_number = $key + 1;
                $question->save();
            }
            DB::commit();
            return redirect()->back()
                ->with('success', 'Payment Method Serialized Successfully');
        }
        catch (\Throwable $th) {
            DB::rollback();
            Log::error($th->getMessage());
            return redirect()->route('admin.payment_method.order')
                ->with('error', 'Failed to update!');
        }
    }

}


