<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\BusinessModel;
use App\Models\ModelVarient;
use App\Models\Plan;
use App\Models\Product;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Product::with(['business_model', 'model_varient', 'plan'])->select(sprintf('%s.*', (new Product())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'product_show';
                $editGate = 'product_edit';
                $deleteGate = 'product_delete';
                $crudRoutePart = 'products';

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
            $table->addColumn('business_model_name', function ($row) {
                return $row->business_model ? $row->business_model->name : '';
            });

            $table->addColumn('model_varient_name', function ($row) {
                return $row->model_varient ? $row->model_varient->name : '';
            });

            $table->addColumn('plan_title', function ($row) {
                return $row->plan ? $row->plan->title : '';
            });

            $table->editColumn('buy_price', function ($row) {
                return $row->buy_price ? $row->buy_price : '';
            });
            $table->editColumn('topup_price', function ($row) {
                return $row->topup_price ? $row->topup_price : '';
            });
            $table->editColumn('reset_price', function ($row) {
                return $row->reset_price ? $row->reset_price : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? Product::STATUS_RADIO[$row->status] : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'business_model', 'model_varient', 'plan']);

            return $table->make(true);
        }

        $business_models = BusinessModel::get();
        $model_varients  = ModelVarient::get();
        $plans           = Plan::get();

        return view('admin.products.index', compact('business_models', 'model_varients', 'plans'));
    }

    public function create()
    {
        abort_if(Gate::denies('product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $business_models = BusinessModel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $model_varients = ModelVarient::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $plans = Plan::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.products.create', compact('business_models', 'model_varients', 'plans'));
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->all());

        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product)
    {
        abort_if(Gate::denies('product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $business_models = BusinessModel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $model_varients = ModelVarient::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $plans = Plan::pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $product->load('business_model', 'model_varient', 'plan');

        return view('admin.products.edit', compact('business_models', 'model_varients', 'plans', 'product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->all());

        return redirect()->route('admin.products.index');
    }

    public function show(Product $product)
    {
        abort_if(Gate::denies('product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->load('business_model', 'model_varient', 'plan');

        return view('admin.products.show', compact('product'));
    }

    public function destroy(Product $product)
    {
        abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $product->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductRequest $request)
    {
        Product::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
