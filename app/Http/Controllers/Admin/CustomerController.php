<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Exception;
use Carbon\Carbon;
use App\Models\JlUser;
use App\Models\Country;
use App\Models\Customer;
use App\Traits\Auditable;
use App\Models\CustomerKycs;
use Illuminate\Http\Request;
use App\Constants\AppConstants;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCustomerRequest;
use App\Helper\Helper;


class CustomerController extends Controller
{
    use CsvImportTrait;
    use Auditable;

    public function index(Request $request)
    {
        abort_if(Gate::denies('customer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Customer::with('customerCountry')->select(sprintf('%s.*', (new Customer())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'customer_show';
                $editGate = 'customer_edit';
                $deleteGate = 'customer_delete';
                $crudRoutePart = 'customers';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('tags', function ($row) {
                if ($row->tags == 1) {
                    $returnHtml = '<span class="badge badge-danger">' . Customer::TAGS[$row->tags] . '</span>';
                } else if ($row->tags == 2) {
                    $returnHtml = '<span class="badge badge-warning">' . Customer::TAGS[$row->tags] . '</span>';
                } else {
                    $returnHtml = '<span class="badge badge-success">' . Customer::TAGS[0] . '</span>';
                }
                return $returnHtml;
            });
            $table->editColumn('customerCountry', function ($row) {
                return $row->customerCountry->name ?? "";
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('created_at', function ($row) {
                return $row->created_at ? frontEndTimeConverterView($row->created_at) : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'tags']);

            return $table->make(true);
        }
        $customerTags = Customer::TAGS;
        return view('admin.customers.index', compact('customerTags'));
    }

    public function create()
    {
        abort_if(Gate::denies('customer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create($request->all());

        return redirect()->route('admin.customers.index');
    }

    public function edit(Customer $customer)
    {
        abort_if(Gate::denies('customer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $customerTags = Customer::TAGS;
        return view('admin.customers.edit', compact('customer', 'customerTags'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {



        DB::beginTransaction();
        try {
            $getApiCustomer = Customer::whereEmail($request->email)->select('id','tags')->first();
            $jlCustomer = JlUser::whereEmail($request->email)->first();
            if (isset($jlCustomer)) {
                $jlCustomer->update(['tags' => $request->tags]);
            }
            $customer->update($request->all());
            // audit
            $properties = array(
               'customer_id' => $getApiCustomer->id,
               'tags' => Customer::TAGS[$request->tags],
               "updated_at" => Carbon::now()->toDateTimeString()
            );
            Auditable::auditLogEntry("Tag:updated", null, "Tags:". Customer::TAGS[$request->tags],$properties);

            Helper::discordAlert(
                "**" . Customer::TAGS[$request->tags] ." ". "Customer**: \nEmail : " .  $request->email
                ."\nCurrent Tag : " . Customer::TAGS[$request->tags]
                ."\nPrevious Tag : " . Customer::TAGS[$getApiCustomer->tags == null ? 0 : $getApiCustomer->tags],
                    true
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        return redirect()->route('admin.customers.index');
    }

    public function show(Customer $customer)
    {
        $customer->load('accounts', 'customerCountry', 'customerKyc.account', 'approvedCustomerKyc');
        abort_if(Gate::denies('customer_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customerTags = Customer::TAGS;
        return view('admin.customers.show', compact('customer', 'customerTags'));
    }

    public function destroy(Customer $customer)
    {
        abort_if(Gate::denies('customer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $customer->delete();

        return back();
    }

    public function massDestroy(MassDestroyCustomerRequest $request)
    {
        Customer::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function kycInfo(Request $request)
    {
        $id = $request->id;
        $kyc = CustomerKycs::find($id);
        return View("admin.customers.kyc", compact('kyc'))->render();
    }
    public function manualKycEntry(Request $request)
    {
        abort_if(Gate::denies('kyc_verification_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $request->validate([
            'customer_name'  => 'required|max:255',
            'customer_email' => 'required',
            'customer_id'    => 'required',
            'reason'         => 'required',
        ]);

        $customerKyc = CustomerKycs::where('customer_id', $request->customer_id)->where('approval_status', CustomerKycs::STATUS_ENABLE)->first();
        if ($customerKyc) {
            return back()->with('danger', "KYC already approved");
        }

        $veriff_id            = 'system-' . uniqid();
        $data                 = [
            'status'       => 'success',
            "verification" => [
                "id"         => $veriff_id,
                "person"     => [
                    'firstName' => $request->customer_name
                ],
                "status"     => 'approved',
                "vendorData" => $request->customer_email,
                "reason"     => $request->reason,
            ]
        ];
        $kyc                  = new CustomerKycs();
        $kyc->customer_id     = $request->customer_id;
        $kyc->veriff_id       = $veriff_id;
        $kyc->kyc_response    = json_encode($data);
        $kyc->status          = AppConstants::KYC_APPROVED;
        $kyc->user_agreement  = CustomerKycs::STATUS_ENABLE;
        $kyc->approval_status = CustomerKycs::STATUS_ENABLE;
        $kyc->save();
        return back()->with('success', "Kyc entry successfully");
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function KycApprovalStatus(Request $request)
    {
        try {
            $id = $request->id;
            $customerKyc                  = CustomerKycs::find($id);
            $customerKyc->approval_status = CustomerKycs::STATUS_ENABLE;
            $customerKyc->save();
            return response()->json(['status' => true], 200);
        } catch (Exception $exception) {
            Log::error("KYC manual approve status", [$exception]);
            return response()->json(['status' => false], 500);
        }
    }
}
