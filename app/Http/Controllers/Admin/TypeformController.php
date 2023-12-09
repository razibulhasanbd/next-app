<?php

namespace App\Http\Controllers\Admin;

use App\Constants\AppConstants;
use App\DataSource\OrderData;
use App\Jobs\InvoiceGenerateJob;
use App\Models\AuditLog;
use App\Models\Coupon;
use App\Models\JlPlan;
use App\Services\Checkout\CouponService;
use App\Services\OrderService;
use App\Services\PhOutsidePaymentService;
use Gate;
use Exception;
use Carbon\Carbon;
use App\Helper\Helper;
use App\Jobs\EmailJob;
use App\Models\Account;
use App\Models\Typeform;
use App\Traits\Auditable;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Constants\EmailConstants;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OutsidePaymentExport;
use App\Exports\ArchivedPaymentExport;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreTypeformRequest;
use App\Http\Requests\UpdateTypeformRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\ApiOutsidePaymentRequest;
use App\Http\Requests\MassDestroyTypeformRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Http\Resources\OutsidePaymentGoogleSheetResource;
use App\Models\TargetReachedAccount;
use App\Models\Orders;

class TypeformController extends Controller
{
    use Auditable;
    public function index(Request $request)
    {

        abort_if(Gate::denies('typeform_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Typeform::whereNull('archived_at')->select(sprintf('%s.*', (new Typeform())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'typeform_show';
                $editGate = 'typeform_edit';
                $deleteGate = 'typeform_delete';
                $crudRoutePart = 'typeforms';

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
            $table->editColumn('payments_for', function ($row) {
                return $row->payments_for ? $row->payments_for : '';
            });
            $table->editColumn('funding_package', function ($row) {
                return $row->funding_package ? $row->funding_package : '';
            });
            $table->editColumn('funding_amount', function ($row) {
                return $row->funding_amount ? $row->funding_amount : '';
            });
            $table->editColumn('coupon_code', function ($row) {
                return $row->coupon_code ? $row->coupon_code : '';
            });
            $table->editColumn('payment_method', function ($row) {
                return $row->payment_method ? $row->payment_method : '';
            });
            $table->editColumn('payment_proof', function ($row) {
                return $row->payment_proof ? '<a href="' . $row->payment_proof . '" target="_blank"><img src="' . $row->payment_proof . '" width="50px" height="50px"></a>' : '';
            });
            $table->editColumn('paid_amount', function ($row) {
                return $row->paid_amount ?  $row->paid_amount : 0;
                // return $row->paid_amount ? '<h5><span class="badge badge-secondary" id="fn_amount'.$row->id.'">'.$row->paid_amount . ' </span></h5> <button type="button" data-toggle="modal" data-target="#modalOpen" class="badge badge-primary" onclick="modalOptions(' .$row->id. ')" >Update</button>' : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('country', function ($row) {
                return $row->country ? $row->country : '';
            });
            $table->editColumn('login', function ($row) {
                return $row->login ? $row->login : '';
            });
            $table->editColumn('payment_verification', function ($row) {

                $approveAccess = Gate::allows('payment_verify_access');
                if ($approveAccess) {

                    $approveClass = '';
                } else {
                    $approveClass = 'noVerifyAccess';
                }
                if ($approveAccess) {
                    if ($row->payment_verification !== null) {
                        if($row->denied_at != null){
                            $status = '<select class="form-control" id="status"  onChange="updatePaymentStatus(this)" strict="true">
                            <option value disabled>Select</option>
                            <option  class="bg-white text-warning font-weight-bold" value="0/' . $row->id . '" ' . ($row->payment_verification == 0 ? 'selected' : '') . '>Pending</option>
                            <option  class="bg-white text-success font-weight-bold" value="1/' . $row->id . '" ' . ($row->payment_verification == 1 ? 'selected' : '') . '>Verified</option>
                            <option class="bg-gray text-primary font-weight-bold" disabled value="2/' . $row->id . '" ' . ($row->payment_verification == 2 ? 'selected' : '') . '>Not Verified</option>
                            <option class="bg-white text-info font-weight-bold" value="3/' . $row->id . '" ' . ($row->payment_verification == 3 ? 'selected' : '') . '>Duplicate</option>
                        </select>';
                                return $status;
                        }else{
                        $status = '<select class="form-control" id="status"  onChange="updatePaymentStatus(this)" strict="true">
                    <option value disabled>Select</option>
                    <option  class="bg-white text-warning font-weight-bold" value="0/' . $row->id . '" ' . ($row->payment_verification == 0 ? 'selected' : '') . '>Pending</option>
                    <option  class="bg-white text-success font-weight-bold" value="1/' . $row->id . '" ' . ($row->payment_verification == 1 ? 'selected' : '') . '>Verified</option>
                    <option class="bg-white text-primary font-weight-bold" value="2/' . $row->id . '" ' . ($row->payment_verification == 2 ? 'selected' : '') . '>Not Verified</option>
                    <option class="bg-white text-danger font-weight-bold" value="3/' . $row->id . '" ' . ($row->payment_verification == 3 ? 'selected' : '') . '>Duplicate</option>
                </select>';
                        return $status;
                    }
                        //return  Typeform::PAYMENT_VERIFICATION_SELECT[$row->payment_verification];
                    } else return '';
                } else {

                    return Typeform::PAYMENT_VERIFICATION_SELECT[$row->payment_verification];
                };
            });

            $table->editColumn('approved_at', function ($row) {

                $approveAccess = Gate::allows('payment_approve_access');
                if ($approveAccess) {

                    $approveClass = '';
                } else {
                    $approveClass = 'notApproved';
                }
                // dd($approveClass);

                if ($row->payment_verification == 1 && $row->approved_at == null) {
                    if ($approveAccess) {

                        if ($row->payments_for == 'Account Reset Fee') {
                            return '<button type="button" id="reset' . $row->id . '" onClick="webhookReset(' . $row->login . ',' . $row->id . ',\'' . 'reset' . '\')"  class="btn btn-primary visible ' . $approveClass . '" >Account Reset</button>';
                        } else if ($row->payments_for == 'New Account' && $row->plan_id == null) {
                            return '<button type="button" id="manual' . $row->id . '" onClick="manuallyCreated(' . $row->login . ',' . $row->id . ',\'' . 'manual' . '\')"  class="btn btn-warning visible ' . $approveClass . '">Manually Created</button>';
                        } else if ($row->payments_for == 'New Account') {
                            return '<button type="button" id="new' . $row->id . '" onClick="webhookForNewAccount(' . $row->id . ',\'' . 'new' . '\')"  class="btn btn-primary visible ' . $approveClass . '">New Account</button>';
                        } else if ($row->payments_for == 'Account TopUp Fee') {
                            return '<button type="button" id="topup' . $row->id . '" onClick="webhookReset(' . $row->login . ',' . $row->id . ',\'' . 'topup' . '\')"  class="btn btn-info visible ' . $approveClass . '">Account TopUp</button>';
                        }
                    } else {

                        return '<p id="notApproved' . $row->id . '"  class="' . $approveClass . '">Not Yet Approved</p>';
                    }
                } else if ($row->payment_verification == 1 && $row->approved_at != null) {

                    return '<p id="notApproved' . $row->id . '" class="' . $approveClass . '">' . frontEndTimeConverterView($row->approved_at) . '</p>';
                    // return $row->approved_at;
                } else {
                    if ($approveAccess) {



                        if ($row->payments_for == 'Account Reset Fee') {
                            return '<button type="button" id="reset' . $row->id . '" onclick="webhookReset(' . $row->login . ',' . $row->id . ',\'' . 'reset' . '\')"  class="btn btn-primary invisible ' . $approveClass . '" >Account Reset</button>';
                        } else if ($row->payments_for == 'New Account' && $row->plan_id == null) {
                            return '<button type="button" id="manual' . $row->id . '" onclick="manuallyCreated(' . $row->login . ',' . $row->id . ',\'' . 'manual' . '\')" class="btn btn-warning invisible ' . $approveClass . '">Manually Created</button>';
                        } else if ($row->payments_for == 'New Account') {
                            return '<button type="button" id="new' . $row->id . '" onClick="webhookForNewAccount(' . $row->id . ',\'' . 'new' . '\')" class="btn btn-primary invisible ' . $approveClass . '">New Account</button>';
                        } else if ($row->payments_for == 'Account TopUp Fee') {
                            return '<button type="button" id="topup' . $row->id . '" onClick="webhookReset(' . $row->login . ',' . $row->id . ',\'' . 'topup' . '\')"  class="btn btn-primary invisible ' . $approveClass . '">Account TopUp</button>';
                        }
                    } else {
                        return '<p id="notApproved' . $row->id . '"  class="' . $approveClass . '">Pending</p>';
                    }
                }
                //return $row->payment_verification ? ($row->payment_verification == 1 ? '<a href="' . route('admin.customers.show', $row->id) . '">Approved</a>' : '<a href="' . route('admin.customers.show', $row->id) . '">' . $row->id . '</a>') : '';
            });
            $table->editColumn('denied_at', function ($row) {
                // return $row->denied_at ?  `'<p id="denied'.$row->id.'" >'.$row->denied_at.'</p>'` : '';
                // return $row->denied_at ? '<span id="denied' . $row->id . '" class="label label-info label-many">' . $row->denied_at . '</span>' : '<span id="denied' . $row->id . '" class="label label-info label-many"></span>';
                return $row->denied_at ? $row->denied_at : '';
            });

            $table->editColumn('transaction_id', function ($row) {
                return $row->transaction_id ? $row->transaction_id : '';
            });


            $table->editColumn('referred_by', function ($row) {
                return $row->referred_by ? $row->referred_by : '';
            });

            $table->addColumn('remarks', function ($row) {
                // return $row->remarks ? '<h5><span class="badge badge-secondary" id="remarks'.$row->id.'">'. Str::limit($row->remarks, 7) . ' </span></h5> <button type="button" data-toggle="modal" data-target="#remarksModalOpen" class="badge badge-primary" onclick="remarksModalOptions(' .$row->id. ')" >Update</button>' : '<h5><span class="badge badge-secondary" id="remarks'.$row->id.'"> </span></h5> <button type="button" data-toggle="modal" data-target="#remarksModalOpen" class="badge badge-primary" onclick="remarksModalOptions(' .$row->id. ')" >Update</button>';
                return $row->remarks ?  $row->remarks : "";
            });
            $table->addColumn('created_at', function ($row) {
                return $row->created_at ? frontEndTimeConverterView($row->created_at) : '';
            });

            $table->addColumn('payment_status', function ($row) {
                return $row->payment_verification ? $row->payment_verification : '0';
            });
            $table->rawColumns(['actions', 'funding_amount', 'remarks', 'paid_amount', 'placeholder', 'payment_proof', 'approved_at', 'denied_at', 'payment_verification', 'created_at']);


            return $table->make(true);
        }

        return view('admin.typeforms.index');
    }


    public function archivedPayments(Request $request)
    {

        abort_if(Gate::denies('typeform_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Typeform::whereNotNull('archived_at')->select(sprintf('%s.*', (new Typeform())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'typeform_show';
                $editGate = 'typeform_edit';
                $deleteGate = 'typeform_delete';
                $crudRoutePart = 'typeforms';

                $ss = view('partials.datatablesActions', compact(
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
            $table->editColumn('payments_for', function ($row) {
                return $row->payments_for ? $row->payments_for : '';
            });
            $table->editColumn('funding_package', function ($row) {
                return $row->funding_package ? $row->funding_package : '';
            });
            $table->editColumn('funding_amount', function ($row) {
                return $row->funding_amount ? $row->funding_amount : '';
            });
            $table->editColumn('coupon_code', function ($row) {
                return $row->coupon_code ? $row->coupon_code : '';
            });
            $table->editColumn('payment_method', function ($row) {
                return $row->payment_method ? $row->payment_method : '';
            });
            $table->editColumn('payment_proof', function ($row) {
                return $row->payment_proof ? '<a href="' . $row->payment_proof . '" target="_blank"><img src="' . $row->payment_proof . '" width="50px" height="50px"></a>' : '';
            });
            $table->editColumn('paid_amount', function ($row) {
                return $row->paid_amount ?  $row->paid_amount : 0;
                // return $row->paid_amount ? '<h5><span class="badge badge-secondary" id="fn_amount'.$row->id.'">'.$row->paid_amount . ' </span></h5> <button type="button" data-toggle="modal" data-target="#modalOpen" class="badge badge-primary" onclick="modalOptions(' .$row->id. ')" >Update</button>' : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('country', function ($row) {
                return $row->country ? $row->country : '';
            });
            $table->editColumn('login', function ($row) {
                return $row->login ? $row->login : '';
            });
            $table->editColumn('payment_verification', function ($row) {

                $approveAccess = Gate::allows('payment_verify_access');
                if ($approveAccess) {

                    $approveClass = '';
                } else {
                    $approveClass = 'noVerifyAccess';
                }
                if ($approveAccess) {
                    if ($row->payment_verification !== null) {
                        if($row->denied_at != null){
                            $status = '<select class="form-control" id="status"  onChange="updatePaymentStatus(this)" strict="true">
                            <option value disabled>Select</option>
                            <option  class="bg-white text-warning font-weight-bold" value="0/' . $row->id . '" ' . ($row->payment_verification == 0 ? 'selected' : '') . '>Pending</option>
                            <option  class="bg-white text-success font-weight-bold" value="1/' . $row->id . '" ' . ($row->payment_verification == 1 ? 'selected' : '') . '>Verified</option>
                            <option class="bg-gray text-primary font-weight-bold" disabled value="2/' . $row->id . '" ' . ($row->payment_verification == 2 ? 'selected' : '') . '>Not Verified</option>
                            <option class="bg-white text-info font-weight-bold" value="3/' . $row->id . '" ' . ($row->payment_verification == 3 ? 'selected' : '') . '>Duplicate</option>
                        </select>';
                                return $status;
                        }else{
                        $status = '<select class="form-control" id="status"  onChange="updatePaymentStatus(this)" strict="true">
                    <option value disabled>Select</option>
                    <option  class="bg-white text-warning font-weight-bold" value="0/' . $row->id . '" ' . ($row->payment_verification == 0 ? 'selected' : '') . '>Pending</option>
                    <option  class="bg-white text-success font-weight-bold" value="1/' . $row->id . '" ' . ($row->payment_verification == 1 ? 'selected' : '') . '>Verified</option>
                    <option class="bg-white text-primary font-weight-bold" value="2/' . $row->id . '" ' . ($row->payment_verification == 2 ? 'selected' : '') . '>Not Verified</option>
                    <option class="bg-white text-danger font-weight-bold" value="3/' . $row->id . '" ' . ($row->payment_verification == 3 ? 'selected' : '') . '>Duplicate</option>
                </select>';
                        return $status;
                    }

                        //return  Typeform::PAYMENT_VERIFICATION_SELECT[$row->payment_verification];
                    } else return '';
                } else {

                    return Typeform::PAYMENT_VERIFICATION_SELECT[$row->payment_verification];
                };
            });

            $table->editColumn('approved_at', function ($row) {
                $approveAccess = Gate::allows('payment_approve_access');
                // dd($approveAccess);
                if ($approveAccess) {

                    $approveClass = '';
                } else {
                    $approveClass = 'notApproved';
                }
                // dd($approveClass);

                if ($row->payment_verification == 1 && $row->approved_at == null) {
                    // dd('1');
                    if ($approveAccess) {

                        if ($row->payments_for == 'Account Reset Fee') {
                            return '<button type="button" id="reset' . $row->id . '" onClick="webhookReset(' . $row->login . ',' . $row->id . ',\'' . 'reset' . '\')"  class="btn btn-primary visible ' . $approveClass . ' ">Account Reset</button>';
                        } else if ($row->payments_for == 'New Account' && $row->plan_id == null) {
                            return '<button type="button" id="manual' . $row->id . '" onClick="manuallyCreated(' . $row->login . ',' . $row->id . ',\'' . 'manual' . '\')"  class="btn btn-warning visible ' . $approveClass . '">Manually Created</button>';
                        } else if ($row->payments_for == 'New Account') {
                            return '<button type="button" id="new' . $row->id . '" onClick="webhookForNewAccount(' . $row->id . ',\'' . 'new' . '\')"  class="btn btn-primary visible ' . $approveClass . '">New Account</button>';
                        } else if ($row->payments_for == 'Account TopUp Fee') {
                            return '<button type="button" id="topup' . $row->id . '" onClick="webhookReset(' . $row->login . ',' . $row->id . ',\'' . 'topup' . '\')"  class="btn btn-info visible ' . $approveClass . '">Account TopUp</button>';
                        }
                    } else {

                        return '<p id="notApproved' . $row->id . '"  class="' . $approveClass . '">Not Yet Approved</p>';
                    }
                } else if ($row->payment_verification == 1 && $row->approved_at != null) {

                    return '<p id="notApproved' . $row->id . '" class="' . $approveClass . '">' . frontEndTimeConverterView($row->approved_at) . '</p>';
                    // return $row->approved_at;
                } else {
                    if ($approveAccess) {



                        if ($row->payments_for == 'Account Reset Fee') {
                            return '<button type="button" id="reset' . $row->id . '" onclick="webhookReset(' . $row->login . ',' . $row->id . ',\'' . 'reset' . '\')"  class="btn btn-primary invisible ' . $approveClass . '" >Account Reset</button>';
                        } else if ($row->payments_for == 'New Account' && $row->plan_id == null) {
                            return '<button type="button" id="manual' . $row->id . '" onclick="manuallyCreated(' . $row->login . ',' . $row->id . ',\'' . 'manual' . '\')" class="btn btn-warning invisible ' . $approveClass . '">Manually Created</button>';
                        } else if ($row->payments_for == 'New Account') {
                            return '<button type="button" id="new' . $row->id . '" onClick="webhookForNewAccount(' . $row->id . ',\'' . 'new' . '\')" class="btn btn-primary invisible ' . $approveClass . '">New Account</button>';
                        } else if ($row->payments_for == 'Account TopUp Fee') {
                            return '<button type="button" id="topup' . $row->id . '" onClick="webhookReset(' . $row->login . ',' . $row->id . ',\'' . 'topup' . '\')"  class="btn btn-primary invisible ' . $approveClass . '">Account TopUp</button>';
                        }
                    } else {
                        return '<p id="notApproved' . $row->id . '"  class="' . $approveClass . '">Pending</p>';
                    }
                }
                //return $row->payment_verification ? ($row->payment_verification == 1 ? '<a href="' . route('admin.customers.show', $row->id) . '">Approved</a>' : '<a href="' . route('admin.customers.show', $row->id) . '">' . $row->id . '</a>') : '';
            });
            $table->editColumn('denied_at', function ($row) {
                // if($row->denied_at != null){
                // }
                // return $row->denied_at ?  `'<p id="denied'.$row->id.'" >'.$row->denied_at.'</p>'` : '';
                // return $row->denied_at ? '<span id="denied' . $row->id . '" class="label label-info label-many">' . $row->denied_at . '</span>' : '<span id="denied' . $row->id . '" class="label label-info label-many"></span>';
                 return $row->denied_at ? $row->denied_at : '';

            });

            $table->editColumn('transaction_id', function ($row) {
                return $row->transaction_id ? $row->transaction_id : '';
            });


            $table->editColumn('referred_by', function ($row) {
                return $row->referred_by ? $row->referred_by : '';
            });

            $table->addColumn('remarks', function ($row) {
                // return $row->remarks ? '<h5><span class="badge badge-secondary" id="remarks'.$row->id.'">'. Str::limit($row->remarks, 7) . ' </span></h5> <button type="button" data-toggle="modal" data-target="#remarksModalOpen" class="badge badge-primary" onclick="remarksModalOptions(' .$row->id. ')" >Update</button>' : '<h5><span class="badge badge-secondary" id="remarks'.$row->id.'"> </span></h5> <button type="button" data-toggle="modal" data-target="#remarksModalOpen" class="badge badge-primary" onclick="remarksModalOptions(' .$row->id. ')" >Update</button>';
                return $row->remarks ?  $row->remarks : "";
            });
            $table->addColumn('created_at', function ($row) {
                return $row->created_at ? frontEndTimeConverterView($row->created_at) : '';
            });

            $table->addColumn('payment_status', function ($row) {
                return $row->payment_verification ? $row->payment_verification : '0';
            });
            $table->addColumn('unarchieve_button', function ($row) {
                return '<a href="' . route('admin.unarchieveOutsidePayment', $row->id) . '"><button type="button" class="btn btn-primary">Unarchieve This</button></a>';
            });

            $table->rawColumns(['actions', 'funding_amount', 'remarks', 'paid_amount', 'placeholder', 'payment_proof', 'approved_at', 'denied_at', 'payment_verification', 'created_at', 'unarchieve_button']);


            return $table->make(true);
        }

        return view('admin.typeforms.archivedPayments');
    }

    public function create()
    {
        abort_if(Gate::denies('typeform_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.typeforms.create');
    }

    public function store(StoreTypeformRequest $request)
    {
        $typeform = Typeform::create($request->all());

        if ($request->input('payment_proof', false)) {
            $typeform->addMedia(storage_path('tmp/uploads/' . basename($request->input('payment_proof'))))->toMediaCollection('payment_proof');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $typeform->id]);
        }

        return redirect()->route('admin.typeforms.index');
    }

    public function edit(Typeform $typeform)
    {
        abort_if(Gate::denies('typeform_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.typeforms.edit', compact('typeform'));
    }

    public function update(UpdateTypeformRequest $request, Typeform $typeform)
    {
        $typeform->update($request->all());

        if ($request->input('payment_proof', false)) {
            if (!$typeform->payment_proof || $request->input('payment_proof') !== $typeform->payment_proof->file_name) {
                if ($typeform->payment_proof) {
                    $typeform->payment_proof->delete();
                }
                $typeform->addMedia(storage_path('tmp/uploads/' . basename($request->input('payment_proof'))))->toMediaCollection('payment_proof');
            }
        } elseif ($typeform->payment_proof) {
            $typeform->payment_proof->delete();
        }

        return redirect()->route('admin.typeforms.index');
    }

    public function show(Typeform $typeform)
    {
        abort_if(Gate::denies('typeform_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.typeforms.show', compact('typeform'));
    }

    public function destroy(Typeform $typeform)
    {
        abort_if(Gate::denies('typeform_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $typeform->delete();

        return back();
    }

    public function massDestroy(MassDestroyTypeformRequest $request)
    {
        Typeform::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('typeform_create') && Gate::denies('typeform_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Typeform();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }


    public function webhookTypeform(Request $request)
    {

        $all_response = $request->all();
        //dd($all_response['form_response']['hidden']['plan_id']);
        $ques_ans_map = [
            'AlRI3jfFjLLb'  => [
                'answer' => 'choice.label',
                'db_column' => 'payments_for',
            ],

            'ovmAKXVTHoBI'  => [
                'answer' => 'choice.label',
                'db_column' => 'funding_package',
            ],

            'dkXHPOMlcndy' => [
                'answer' => 'choice.label',
                'db_column' => 'funding_amount',
            ],

            'Ktv0ZdEwZOKV' => [
                'answer' => 'choice.label',
                'db_column' => 'funding_amount',
            ],

            'TKs7DofIwcDS' => [
                'answer' => 'choice.label',
                'db_column' => 'payment_method',
            ],

            'ZDYiwTqbbcuE' => [
                'answer' => 'file_url',
                'db_column' => 'payment_proof',
            ],

            'urSAOwD2xNJG' => [
                'answer' => 'text',
                'db_column' => 'paid_amount',
            ],

            'a4aFOMDxLVvt' => [
                'answer' => 'text',
                'db_column' => 'transaction_id',
            ],


            '90tqqYbXOnen' => [
                'answer' => 'text',
                'db_column' => 'coupon_code',
            ],

            'UMMh4nk58VDU' => [
                'answer' => 'text',
                'db_column' => 'login',
            ],

            'J5A10Cj7TNzn' => [
                'answer' => 'text',
                'db_column' => 'name',
            ],

            'Z88Bdwt1pliw' => [
                'answer' => 'email',
                'db_column' => 'email',
            ],

            'KUXr8GjTIbAc' => [
                'answer' => 'choice.label',
                'db_column' => 'country',
            ],

            'agxcPw5V9FD1' => [
                'answer' => 'text',
                'db_column' => 'referred_by',
            ],
        ];



        $webhook = $all_response['form_response']['answers'];
        $newPayment = [];
        foreach ($webhook as $answer) {
            if (!isset($ques_ans_map[$answer['field']['id']])) continue;
            $fieldId =  $answer['field']['id'];

            $newPayment[$ques_ans_map[$fieldId]['db_column']] =  Arr::get($answer, $ques_ans_map[$fieldId]['answer']);
        }
        //dd($newPayment['payment_proof']);
        if (isset($all_response['form_response']['hidden']['plan_id']) && $all_response['form_response']['hidden']['plan_id'] != 'xxxxx' && $all_response['form_response']['hidden']['plan_id'] != 'null') {
            $newPayment['plan_id'] = $all_response['form_response']['hidden']['plan_id'];
        }
        $imageUrl = $newPayment['payment_proof'];
        $apiKey = env('OCR_API_KEY');
        $ocr = Http::get(env('OCR_IMAGE_URL') . "apikey='.$apiKey.'&url=" . $imageUrl);
        Log::info(json_encode($ocr, JSON_PRETTY_PRINT));
        if ($ocr->successful() && isset($ocr['ParsedResults'][0]['ParsedText'])) {
            $imageTextCount = strlen($ocr['ParsedResults'][0]['ParsedText']);
        } else {

            return response()->json(['message' => 'Something Went Wrong!'], 400);
        }
        if ($imageTextCount < 10) {
            $newPayment['archived_at'] = Carbon::now();
        }

        Typeform::create($newPayment);
        return "Ok";
    }


    public function webhookOutsidePayment(ApiOutsidePaymentRequest $request)
    {
        try {
            if ($request->payment_proof == null) {
                return response()->json(['message' => "Image is required"], 400);
            }
        //    $typeFormCheck = Typeform::whereDate('created_at', Carbon::today())->count();
        //    if ($typeFormCheck >= 5){ // max 5 time
        //        return response()->json(['message' => "Your daily quota (5 times) is already excited for today. Please try again later or knock live chat."], 400);
        //    }
            $extension = $request->file('payment_proof')->extension();
            $path = Storage::disk('paymentProofs')->put('manual-payment', $request->file('payment_proof'), ['visibility' => 'public']);
            $newPayment = $request->all();


            $apiKey = env('OCR_API_KEY');
            $ocr = Http::get(env('OCR_IMAGE_URL') . "apikey='.$apiKey.'&url=" . env('DO_URL') . $path);
            Log::info(json_encode($ocr, JSON_PRETTY_PRINT));
            if ($ocr->successful() && isset($ocr['ParsedResults'][0]['ParsedText'])) {
                $imageTextCount = strlen($ocr['ParsedResults'][0]['ParsedText']);
            } else {
                $imageTextCount = 11;
                Helper::discordAlert("**OCR**:\nOcr Body: " . json_encode($ocr, JSON_PRETTY_PRINT));
                //return response()->json(['message' => 'Something Went Wrong!'], 400);
            }

            if ($imageTextCount < 10) {
                $newPayment['archived_at'] = Carbon::now();
            }
            $newPayment['payment_proof'] = env('DO_URL') . $path;
            $newPayment['transaction_id'] = isset($newPayment['transaction_id']) ? $newPayment['transaction_id'] : '-';
            $newPayment['coupon_code'] = isset($newPayment['coupon']) ? $newPayment['coupon'] : '';
            if(isset($newPayment['country_id']) && is_numeric($newPayment['country_id'])){
                $newPayment['country_id'] = $newPayment['country_id'];
            }
            else{
                $newPayment['country_id'] = null;
            }
            $typeForm = Typeform::create($newPayment);

            Log::info("typeform",[$typeForm]);

            if($typeForm){
                if($typeForm->payments_for == "New Account"){
                    $details    = [
                        'template_id' => EmailConstants::OUTSIDE_PAYMENT_REQUEST_RECEIVED_FOR_NEW_ACCOUNT,
                        'to_name'     => Helper::getOnlyCustomerName($typeForm->name),
                        'to_email'    => $typeForm->email,
                        'email_body'  => [
                            'name' => Helper::getOnlyCustomerName($typeForm->name),
                            'payments_for'=> "new account",
                            'outside_payment_request_date' => Carbon::now()->format('d-m-y'). " " . config('app.timezone_utc'),
                        ]
                    ];
                    Log::info("New Account", [$typeForm->payments_for]);

                }elseif($typeForm->payments_for == "Account TopUp Fee"){
                    $details    = [
                        'template_id' => EmailConstants::OUTSIDE_PAYMENT_REQUEST_RECEIVED_FOR_TOPUP_RESET,
                        'to_name'     => Helper::getOnlyCustomerName($typeForm->name),
                        'to_email'    => $typeForm->email,
                        'email_body'  => [
                            'name' => Helper::getOnlyCustomerName($typeForm->name),
                            'login_id'=>$typeForm->login,
                            'payments_for'=> "account topup",
                            'outside_payment_request_date' => Carbon::now()->format('d-m-y'). " " . config('app.timezone_utc'),
                        ]
                    ];
                    Log::info("account topup",[$typeForm->payments_for]);
                }else{
                    $details    = [
                        'template_id' => EmailConstants::OUTSIDE_PAYMENT_REQUEST_RECEIVED_FOR_TOPUP_RESET,
                        'to_name'     => Helper::getOnlyCustomerName($typeForm->name),
                        'to_email'    => $typeForm->email,
                        'email_body'  => [
                            'name' => Helper::getOnlyCustomerName($typeForm->name),
                            'login_id'=>$typeForm->login,
                            'payments_for'=> "account reset",
                            'outside_payment_request_date' => Carbon::now()->format('d-m-y'). " " . config('app.timezone_utc'),
                        ]
                    ];
                    Log::info("account reset",[$typeForm->payments_for]);

                }
                EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
            }

            return response()->json(['message' => "Record Created Successfully", 'success' => true, 'payment_proof' => $imageTextCount < 10 ? false : true], 200);
        } catch (Exception $exception) {
            Log::error("TypeFormController::webhookOutsidePayment", [$exception]);
            return response()->json(['message' => "INTERNAL SERVER ERROR", 'success' => false], 500);
        }
    }


    public function changeManualPaymentStatus(Request $request)
    {
        $test =  $request['statusId'];
        $typeFormId = substr($test, strpos($test, "/") + 1);
        $paymentverificationId = strtok($test, '/');

        $typeForm =  Typeform::find($typeFormId);
        $plan_id = $typeForm->plan_id;
        $payments_for = $typeForm->payments_for;

        $typeForm_name=$typeForm->name;
        $typeForm_email=$typeForm->email;
        $typeForm_login=$typeForm->login;

        if ($paymentverificationId == "1") {
            // verified
            $typeForm->update(["payment_verification" => $paymentverificationId, "denied_at" => '']);

            $model = array(
                'properties' => array('login' => '', 'payment_verification' => "Verified"),
            );
            $model = json_encode($model);
            $model = json_decode($model);
            $this->audit("outsidePayments:verified", $model);

            return response()->json(['message' => 'Payment Verification status change', 'denied_at' => $typeForm->denied_at, 'payments_for' => $payments_for, 'plan_id' => $plan_id, 'result' => $typeForm], 200);
        } else if ($paymentverificationId == "2") {
            // not verified
            $typeForm->update(["payment_verification" => $paymentverificationId, "denied_at" => Carbon::now()]);
            $model = array(
                'properties' => array('login' => '', 'payment_verification' => "NotVerified"),
            );
            $model = json_encode($model);
            $model = json_decode($model);
            $this->audit("outsidePayments:NotVerified", $model);

            Log::info("status",[$typeForm]);
            if($payments_for == "New Account"){
                $details    = [
                    'template_id' => EmailConstants::OUTSIDE_PAYMENT_REQUEST_REJECTED_FOR_NEW_ACCOUNT,
                    'to_name'     => Helper::getOnlyCustomerName($typeForm_name),
                    'to_email'    => $typeForm_email,
                    'email_body'  =>
                        [
                            'name' => Helper::getOnlyCustomerName($typeForm_name),
                            'payments_for'=> "new account",
                            'outside_payment_request_date' => Carbon::now()->format('d-m-y'). " " . config('app.timezone_utc'),
                        ]
                ];
                Log::info("status new account ",[$payments_for]);

            }else if($payments_for == "Account TopUp Fee"){
                $details    = [
                    'template_id' => EmailConstants::OUTSIDE_PAYMENT_REQUEST_REJECTED_FOR_TOPUP_RESET,
                    'to_name'     =>Helper::getOnlyCustomerName($typeForm_name),
                    'to_email'    => $typeForm_email,
                    'email_body'  =>
                        [
                            'name' => Helper::getOnlyCustomerName($typeForm_name),
                            'login_id'=> $typeForm_login,
                            'payments_for'=> "account topup",
                            'outside_payment_request_date' => Carbon::now()->format('d-m-y'). " " . config('app.timezone_utc'),
                        ]
                ];
                Log::info("status topup ",[$payments_for]);

            }else{
                $details    = [
                    'template_id' => EmailConstants::OUTSIDE_PAYMENT_REQUEST_REJECTED_FOR_TOPUP_RESET,
                    'to_name'     => Helper::getOnlyCustomerName($typeForm_name),
                    'to_email'    => $typeForm_email,
                    'email_body'  =>
                        [
                            'name' => Helper::getOnlyCustomerName($typeForm_name),
                            'login_id'=> $typeForm_login,
                            'payments_for'=> "account reset",
                            'outside_payment_request_date' => Carbon::now()->format('d-m-y'). " " . config('app.timezone_utc'),
                        ]
                ];
                Log::info("status reset ",[$payments_for]);

            }

            EmailJob::dispatch($details)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);

            return response()->json(['message' => 'Payment Verification status change', 'denied_at' => $typeForm->denied_at, 'payments_for' => $payments_for, 'plan_id' => $plan_id, 'result' => $typeForm], 200);
        } else {
            // pending
            $typeForm->update(["payment_verification" => $paymentverificationId, "denied_at" => '']);
            $model = array(
                'properties' => array('login' => '',  'payment_verification' => "pending"),
            );
            $model = json_encode($model);
            $model = json_decode($model);
            $this->audit("outsidePayments:pending", $model);
            return response()->json(['message' => 'Payment Verification status change', 'denied_at' => $typeForm->denied_at, 'payments_for' => $payments_for, 'plan_id' => $plan_id, 'result' => $typeForm], 200);
        }
    }




    public function fundingAmount(Request $request)
    {
        if(!is_numeric($request->modalInput)){
            return response()->json(['message' => "amount must be neumeric"],400);
        }

        $newFundingAmount = $request->modalInput;
        $rowId = $request->rowId;
        $typeForm =  Typeform::find($rowId);
        $typeForm->update(["paid_amount" => $newFundingAmount]);
        $model = array(
            'properties' => array('login' => '',  'paid_amount' => $newFundingAmount),
        );
        $model = json_encode($model);
        $model = json_decode($model);
        $this->audit("outsidePayments:updateAmount", $model);
        return response()->json(['row_id' => $rowId, 'fn_amount' => $newFundingAmount]);
    }

    public function remarksUpdate(Request $request)
    {

        $newRemarksUpdate = $request->newRemarks;
        $rowId = $request->rowId;
        $typeForm =  Typeform::find($rowId);
        $typeForm->update(["remarks" => $newRemarksUpdate]);
        return response()->json(['row_id' => $rowId, 'newRemarks' => $newRemarksUpdate]);
    }




    public function webhookTopupReset(Request $request)
    {
        $account = Account::with('customer')->whereLogin($request->brokerNumber)->first();
        $typeForm =  Typeform::whereId($request->rowId)->whereLogin($account->login)->first();
        if ($account->customer->email === $typeForm->email) {
            $topupOrReset = Http::withHeaders([
                'Accept' => 'application/json',
                'X-Verification-Key' => env('WEBHOOK_TOKEN'),
            ])->post(env('FRONTEND_URL') . "/api/v1/webhook/topup-reset-request", [
                "brokerNumber" => $account->id,
                "note" => "Wehbook : $request->type",
                "type" => $request->type,
            ]);
            if ($topupOrReset->successful()) {
                $typeform =  Typeform::whereId($request->rowId)->whereLogin($account->login)->first();
                if($request->type == 'reset'){
                    $order_type = AppConstants::PRODUCT_ORDER_RESET;
                    $type = $typeform->funding_package. " Reset";
                }else{
                    $order_type = AppConstants::PRODUCT_ORDER_TOPUP;
                    $type = $account->funding_package. " TopUp";
                }
                $payments_for = $typeForm->payments_for;
                $typeform->update(["approved_at" => Carbon::now()]);
                $model = array(
                    'properties' => array('login' => $account->login,  'type' => $request->type),
                );
                $model = json_encode($model);
                $model = json_decode($model);
                $this->audit("outsidePayments:Wehbook : $request->type", $model);
                $transaction_id = "OP-" . uniqid() . "-" . $typeform->transaction_id;
                $response = (new PhOutsidePaymentService)->phOutsidePayment($typeForm->name, $typeForm->email, $typeForm->paid_amount, $transaction_id, $type);
                if(!$response->successful()){
                    Log::error("PH order generate issue ", [$response]);
                    return response()->json(['message' => 'Account ' . $request->type . ' successfully. But the order can not generate because of some issues. Please generate the order manually',  'approved_at' => $typeForm->approved_at->toDateTimeString(), 'button_id' => $typeForm->id, 'payments_for' => $payments_for], 200);
                }
                //order data generate
                $this->generateOrder($typeForm, $order_type, $account, $transaction_id);

                return response()->json(['message' => 'Account ' . $request->type . ' successfully',  'approved_at' => $typeform->approved_at->toDateTimeString(), 'button_id' => $typeForm->id, 'payments_for' => $payments_for, 'message2' => null], 200);
            } else {
                return response()->json(['message' => 'Something Went Wrong!', 'message2' => null], 400);
            }
        } else {

            return response()->json(['message2' => 'Not Same In Email!'], 400);
        }
    }

    public function webhookNewAccount(Request $request)
    {

        $typeform = Typeform::find($request->id);
        $nameArray = explode(" ", $typeform->name, 2);
        $newAccount = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Verification-Key' => env('WEBHOOK_TOKEN'),
        ])->post(env('FRONTEND_URL') . "/api/v1/webhook/create-subscription", [
            "firstName" => $nameArray[0],
            "lastName" => $nameArray[1] ?? "FundedNext",
            "email" => $typeform->email,
            "password" => $typeform->password ?? 'secret',
            "server_name" => $typeform->server_name,
            "planId" => $typeform->plan_id,
            "subscriptionType" => 2,
            "remarks" => "Wehbook New Account",
            "country_id" => $typeform->country_id ?? null,
        ]);
        dd($newAccount);

        if ($newAccount->successful()) {
            $typeForm = Typeform::find($request->id);

            $payments_for = $typeForm->payments_for;
            $typeForm->approved_at = Carbon::now();
            $typeForm->save();
            $model = array(
                'properties' => array('login' => '',  'type' => "new account"),
            );
            $model = json_encode($model);
            $model = json_decode($model);
            $this->audit("outsidePayments:Wehbook new account", $model);
            $transaction_id = "OP-" . uniqid() . "-" . $typeform->transaction_id;
            $response = (new PhOutsidePaymentService)->phOutsidePayment($typeform->name, $typeform->email, $typeform->paid_amount, $transaction_id, $typeform->funding_package . " New");
            if (!$response->successful()) {
                Log::error("PH order generate issue ", [$response]);
                return response()->json(['message' => 'Account ' . $request->type . ' successfully. But the order can not generate because of some issues. Please generate the order manually',  'approved_at' => $typeForm->approved_at->toDateTimeString(), 'button_id' => $typeForm->id, 'payments_for' => $payments_for], 200);
            }

            //order data generate
            $this->generateOrder($typeform, AppConstants::PRODUCT_ORDER_NEW_ACCOUNT, null, $transaction_id);

            return response()->json(['message' => 'Account ' . $request->type . ' successfully',  'approved_at' => $typeForm->approved_at->toDateTimeString(), 'button_id' => $typeForm->id, 'payments_for' => $payments_for], 200);
        } else {
            return response()->json(['message' => 'Something Went Wrong!'], 400);
        }
    }


    public function manuallyCreated(Request $request)
    {
        $typeForm = Typeform::find($request->id);
        if (isset($typeForm) && $typeForm != null) {

            $payments_for = $typeForm->payments_for;
            $typeForm->update(["approved_at" => Carbon::now()]);
            $model = array(
                'properties' => array('login' => '',  'type' => "new account"),
            );
            $model = json_encode($model);
            $model = json_decode($model);
            $this->audit("outsidePayments:Manually Created", $model);

            return response()->json(['message' => 'Account ' . $request->type . ' successfully',  'approved_at' => $typeForm->approved_at->toDateTimeString(), 'button_id' => $typeForm->id, 'payments_for' => $payments_for], 200);
        } else {
            return response()->json(['message' => 'Something Went Wrong!'], 400);
        }
    }
    public function unarchieveOutsidePayment(Request $request)
    {
        $outsidePaymentRow = Typeform::find($request->id);
        if (isset($outsidePaymentRow) && $outsidePaymentRow != null) {
            $model = array(
                'properties' => array('login' => $outsidePaymentRow->login,  'type' => $outsidePaymentRow->payments_for),
            );
            $model = json_encode($model);
            $model = json_decode($model);
            $this->audit("outsidePayments:Unarchieved", $model);
            $outsidePaymentRow->update(['archived_at' => null]);
            return redirect()->route('admin.typeforms.archivedPayments');
        } else {
            return redirect()->route('admin.typeforms.archivedPayments');
        }
    }

    public function googleSheetRecords()
    {
        return OutsidePaymentGoogleSheetResource::collection(Typeform::select('typeforms.*', 'plans.title as plan_title')->join('plans', 'typeforms.plan_id', '=', 'plans.id')->get());
    }

    public function downloadOutsidePayment(Request $request)
    {
        // dd($request->all());
        return Excel::download(new OutsidePaymentExport, 'outsidePayments.csv');
    }

    public function downloadArchivedPayment(Request $request)
    {
        // dd($request->all());
        return Excel::download(new ArchivedPaymentExport, 'archivedPayments.csv');
    }

    public function showHistory(Request $request)
    {
        $historyData = AuditLog::with('user')->where('subject_type', 'App\Models\Typeform#' . $request->rowId)
            ->orderBy('id','DESC')->get();
        return View("admin.typeforms.history-data", compact('historyData'))->render();
    }

    public function transactionIdUpdate(Request $request)
    {
        $transactionId = $request->modalInput;
        $rowId         = $request->rowId;
        $typeForm      = Typeform::find($rowId);
        $typeForm->update(["transaction_id" => $transactionId]);
        $model = array(
            'properties' => array('login' => '', 'transaction_id' => $transactionId),
        );
        $model = json_encode($model);
        $model = json_decode($model);
        $this->audit("outsidePayments:updateTransaction", $model);
        return response()->json(['row_id' => $rowId, 'transaction_id' => $transactionId]);
    }


    public function generateOrder(Typeform $typeform, $orderType, Account $account = null, $transaction_id){
        $plan   = JlPlan::find($typeform->plan_id);
        if ($orderType == AppConstants::PRODUCT_ORDER_TOPUP) {
            $amount = $plan->topup_charge;
        } elseif ($orderType == AppConstants::PRODUCT_ORDER_RESET) {
            $amount = $plan->reset_charge;
        }else{
            $amount = $plan->price;
        }

        if ($typeform->coupon_code) {
            $coupon = Coupon::where('code', $typeform->coupon_code)->first();
            if ($coupon) {
                $couponDiscountPrice = CouponService::couponPrice($coupon, $plan);
                $amount              = $couponDiscountPrice['payable_amount'];
            }
        }
        $orderData                  = new OrderData();
        $orderData->accountId       = isset($account) ? $account->id : null;
        $orderData->customerId      = isset($account->customer) ? $account->customer->id : null;
        $orderData->email           = $typeform->email;
        $orderData->orderType       = $orderType;
        $orderData->gateway         = AppConstants::GATEWAY_OUTSIDE;
        $orderData->transactionId   = $transaction_id;
        $orderData->couponId        = isset($coupon) ? $coupon->id : null;
        $orderData->status          = Orders::STATUS_ENABLE;
        $orderData->jlPlanId        = $plan->id;
        $orderData->billing_address = null;
        $orderData->serverName      = $typeform->server_name;
        if (isset($coupon) && isset($couponDiscountPrice)) {
            $orderData->total     = $couponDiscountPrice['old_amount'] / 100;
            $orderData->discount  = $couponDiscountPrice['coupon_amount'] / 100;
        } else {
            $orderData->total     = $amount / 100;
            $orderData->discount  = 0;
        }
        $orderData->gradTotal = $typeform->paid_amount;

        $order = (new OrderService())->generateOrder($orderData);

        /**
         * Dispatching order email with invoice pdf attachment.
        */
        InvoiceGenerateJob::dispatch($order->id, $orderType)->onQueue(AppConstants::QUEUE_DEFAULT_JOB);
    }
}
