<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdateCountryCategoryRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use App\Models\Country;
use App\Services\PaymentMethodService;
use App\Traits\Auditable;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class CountryCategoryController extends Controller
{
    use Auditable;
    public $layoutFolder = "admin.payment_setting.country";
    public $paymentMethodService;

    public function __construct()
    {
        $this->paymentMethodService = new PaymentMethodService();
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('payment_method_country_list'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = Country::query();


        if($request->filled('country_category')){
            $query->where('country_category', $request->country_category);
        }
        if($request->filled('name')){
            $query->where('name', 'LIKE', '%'.$request->name . '%');
        }


        $query =   $query->orderBy('name', 'asc');
        $countries = ($request->pagination ? $query->paginate($request->pagination) : $query->paginate(20));
        $paymentCountryCategory = $this->paymentMethodService->paymentCountryCategory();
        return view("{$this->layoutFolder}.index", compact('countries','paymentCountryCategory'));
    }

    public function edit(Country $country)
    {
        $paymentCountryCategory = $this->paymentMethodService->paymentCountryCategory();
        return view("{$this->layoutFolder}.edit", compact('country','paymentCountryCategory'));
    }

    public function swapCategory(UpdateCountryCategoryRequest $request, Country $country)
    {
        abort_if(Gate::denies('payment_method_country_category_swap'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        try{
            $country->country_category = $request->country_category;
            $country->save();
            self::auditLogEntry("CountryCategory:updated", $country->id,"App\Models\Country", $country );

            return redirect()->route('admin.payment_method.country_list.index')
                ->with('success', 'Country category updated successfully.');
        }catch (\Exception $exception){

            Log::error("CountryCategoryController::swapCategory()" . $exception);
        }
        return redirect()->back()
            ->withErrors(['message', 'Failed!']);
    }


}
