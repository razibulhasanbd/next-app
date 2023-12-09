<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Api\V1\Admin\CertificateController;
use Gate;
use Carbon\Carbon;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Ceritificate;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\AccountCertificate;
use App\Http\Controllers\Controller;
use App\Models\TargetReachedAccount;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Imagick;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\StoreAccountCertificateRequest;
use App\Http\Requests\UpdateAccountCertificateRequest;
use App\Http\Requests\MassDestroyAccountCertificateRequest;

class AccountCertificateController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('account_certificate_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = AccountCertificate::with(['certificate', 'account', 'customer', 'subscription'])->select(sprintf('%s.*', (new AccountCertificate())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'account_certificate_show';
                $editGate = 'account_certificate_edit';
                $deleteGate = 'account_certificate_delete';
                $crudRoutePart = 'account-certificates';

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
            $table->addColumn('certificate_name', function ($row) {
                return $row->certificate ? $row->certificate->name : '';
            });

            $table->addColumn('account_login', function ($row) {
                return $row->account ? $row->account->login : '';
            });

            $table->editColumn('certificate_data', function ($row) {
                return $row->certificate_data ? $row->certificate_data : '';
            });
            $table->addColumn('customer_name', function ($row) {
                return $row->customer ? $row->customer->name : '';
            });

            $table->editColumn('customer.email', function ($row) {
                return $row->customer ? (is_string($row->customer) ? $row->customer : $row->customer->email) : '';
            });
            $table->editColumn('url', function ($row) {
                return $row->url ? $row->url : '';
            });
            $table->editColumn('share', function ($row) {
                return $row->share == "1" ? AccountCertificate::SHARE_RADIO[$row->share] : AccountCertificate::SHARE_RADIO[$row->share];
            });
            $table->addColumn('subscription_account', function ($row) {
                return $row->subscription ? frontEndTimeConverterView($row->subscription->created_at) : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'certificate', 'account', 'customer', 'subscription']);

            return $table->make(true);
        }

        $ceritificates = Ceritificate::get();
        $accounts      = Account::get();
        $customers     = Customer::get();

        return view('admin.accountCertificates.index', compact('ceritificates', 'accounts', 'customers'));
    }

    public function create()
    {
        abort_if(Gate::denies('account_certificate_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $certificates = Ceritificate::pluck('name', 'html_markup')->prepend(trans('global.pleaseSelect'), '');

        $accounts = Account::pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');

        $customers = Customer::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $subscriptions = Subscription::pluck('account_id', 'id')->prepend(trans('global.pleaseSelect'), '');

        $certificate_text = array(
            "SUBSCRIPTION FEE REFUND" => "SUBSCRIPTION FEE REFUND",
            "FUNDED ACCOUNT PROFIT SHARE" => "FUNDED ACCOUNT PROFIT SHARE",
            "PHASE 1 & 2 (15% PROFIT SHARE)" => "PHASE 1 & 2 (15% PROFIT SHARE)",
            "TOTAL PROFIT MADE" => "TOTAL PROFIT MADE",
            "80% PROFIT SPLIT" => "80% PROFIT SPLIT",
        );

        return view('admin.accountCertificates.create', compact('accounts', 'certificates', 'customers', 'certificate_text', 'subscriptions'));
    }

    public function store(StoreAccountCertificateRequest $request)
    {

        $certificate_name = Ceritificate::where('html_markup', $request->certificate_id)->first();
        $account_info = Account::with('plan', 'customer')->find($request->account_id);
        $data = [
            "currentProfit" => $request->currentProfit,
            "model" => $model = strtok($account_info->plan->type, " "),
            "totalShared" => $request->totalShared,
            "variant" => $certificate_name->html_markup,
            "breakdown" => $merged = collect($request->text)->zip($request->value)->transform(function ($values) {
                return [
                    'key' => $values[0],
                    'value' => $values[1],
                ];
            })

        ];

        $doc_id = explode('.', explode('@aa@',$request->url)[1]);
        $account_certificate = [
            "certificate_data" => json_encode($data),
            "certificate_id" => $certificate_name->id,
            "account_id" => $request->account_id,
            "customer_id" => $account_info->customer->id,
            "subscription_id" => $request->subscription_id,
            "url" => $request->url,
            "doc_id" => current($doc_id)

        ];
        AccountCertificate::create($account_certificate);
        return redirect()->route('admin.account-certificates.index');
    }

    public function certificateCurrentProfit(Request $request)
    {
        $targetReachedInfo = TargetReachedAccount::where('account_id', $request->account_id)->latest()->first();
        $metric_info = $targetReachedInfo ? json_decode($targetReachedInfo->metric_info) : 0;
        $currentProfit = $targetReachedInfo ? $metric_info->balance - $metric_info->starting_balance : 0;
        $currentProfit = round($currentProfit, 2);
        return response()->json(['message' => "Current Profit", 'currentProfit' => $currentProfit], 200);
    }


    public function certificatePreview(Request $request)
    {
//        dd($request->all());
        $certificate_name = Ceritificate::where('html_markup', $request->certificate_id)->first();
        $account_info = Account::with('plan', 'customer')->find($request->account_id);
        $preview = [
            "fullname" => $account_info->customer->name,
            "accountId" => $account_info->id,
            "login" => $account_info->login,
            "current_profit" => $request->currentProfit,
            "certificate_data" => [
                "model" => $model = strtok($account_info->plan->type, " "),
                "totalShared" => $request->totalShared,
                "variant" => $certificate_name->html_markup,
                "breakdown" => $merged = collect($request->text)->zip($request->value)->transform(function ($values) {
                    return [
                        'key' => $values[0],
                        'value' => $values[1],
                    ];
                })
            ],
            "created_at" => Carbon::now()->toDateTimeString(),
            "certTypeId" => $certificate_name->id
        ];
        $certificateController = new CertificateController();
        $response = $certificateController->generateCertificate($preview);
        return response()->json(['message' => "Certificate Preview", 'response' => $response], 200);
    }

    public function edit(AccountCertificate $accountCertificate)
    {
        abort_if(Gate::denies('account_certificate_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $certificates = Ceritificate::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $accounts = Account::pluck('login', 'id')->prepend(trans('global.pleaseSelect'), '');

        $customers = Customer::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $accountCertificate->load('certificate', 'account', 'customer');

        return view('admin.accountCertificates.edit', compact('accountCertificate', 'accounts', 'certificates', 'customers'));
    }

    public function update(UpdateAccountCertificateRequest $request, AccountCertificate $accountCertificate)
    {
        $accountCertificate->update($request->all());

        return redirect()->route('admin.account-certificates.index');
    }

    public function show(AccountCertificate $accountCertificate)
    {
        abort_if(Gate::denies('account_certificate_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accountCertificate->load('certificate', 'account', 'customer');

        return view('admin.accountCertificates.show', compact('accountCertificate'));
    }

    public function destroy(AccountCertificate $accountCertificate)
    {
        abort_if(Gate::denies('account_certificate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $accountCertificate->delete();

        return back();
    }

    public function massDestroy(MassDestroyAccountCertificateRequest $request)
    {
        AccountCertificate::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function getSubscription(Request $request)
    {

        $accountGetAllSubs = Subscription::where('account_id', $request->account_id)->orderBy('id', 'desc')
            ->get();
        $accountGetAllSubs = $accountGetAllSubs->map(function ($modifyDate) {
            $modifyDate->sub_createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $modifyDate->created_at)->format('d F Y');
            $modifyDate->sub_endingAt = Carbon::createFromFormat('Y-m-d H:i:s', $modifyDate->ending_at)->format('d F Y');
            return $modifyDate;
        });
        return response()->json([
            'accountGetAllSubs' => $accountGetAllSubs
        ]);
    }

    /**
     * Get trading info
     * @return \Illuminate\Http\Response
     */

    public function certificateDelete(Request $request):JsonResponse
    {
        try{
            abort_if(Gate::denies('account_certificate_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

            $imageFile = $request->certificate_img_url;
            $pdfFile = str_replace('.jpeg', '.pdf', $request->certificate_img_url);
            Storage::disk('utility-files')->delete([$imageFile,$pdfFile]);
            return \response()->json(['status'=>true, 'messages'=> 'File delete successfully']);
        }catch (\Exception $exception){
            return \response()->json(['status'=>false, 'messages'=> 'Something was wrong!']);
        }

    }
}
