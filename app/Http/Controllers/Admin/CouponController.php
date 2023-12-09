<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCouponRequest;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Models\Coupon;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CouponController extends Controller
{
    use MediaUploadingTrait;
    use CsvImportTrait;
    public $types = Coupon::TYPE_ARRAY;

    public function index(Request $request)
    {
        abort_if(Gate::denies('coupon_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Coupon::query()->select(sprintf('%s.*', (new Coupon())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'coupon_show';
                $editGate = 'coupon_edit';
                $deleteGate = 'coupon_delete';
                $crudRoutePart = 'coupons';

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
            $table->editColumn('type', function ($row) {
                return $row->type ? $this->types[$row->type] : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : '';
            });

            $table->editColumn('max_redemption', function ($row) {
                return $row->max_redemption ? $row->max_redemption : '';
            });
            $table->editColumn('max_redemption_per_user', function ($row) {
                return $row->max_redemption_per_user ? $row->max_redemption_per_user : '';
            });
            $table->editColumn('amount', function ($row) {
                return $row->amount ? $row->amount : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status == Coupon::STATUS_ENABLE ? '<span class="badge bg-success text-white">Enabled</span>' : '<span class="badge bg-danger text-white">Disabled</span>';
            });

            $table->rawColumns(['actions', 'placeholder','status']);

            return $table->make(true);
        }

        return view('admin.coupons.index');
    }

    public function create()
    {
        abort_if(Gate::denies('coupon_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $types = $this->types;
        return view('admin.coupons.create',compact('types'));
    }

    public function store(StoreCouponRequest $request)
    {
        $coupon = Coupon::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $coupon->id]);
        }

        return redirect()->route('admin.coupons.index');
    }

    public function edit(Coupon $coupon)
    {
        abort_if(Gate::denies('coupon_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $types = $this->types;
        return view('admin.coupons.edit', compact('coupon','types'));
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $coupon->update($request->all());

        return redirect()->route('admin.coupons.index');
    }

    public function show(Coupon $coupon)
    {
        abort_if(Gate::denies('coupon_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $types = $this->types;
        return view('admin.coupons.show', compact('coupon','types'));
    }

    public function destroy(Coupon $coupon)
    {
        abort_if(Gate::denies('coupon_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $coupon->delete();

        return back();
    }

    public function massDestroy(MassDestroyCouponRequest $request)
    {
        Coupon::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('coupon_create') && Gate::denies('coupon_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Coupon();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
