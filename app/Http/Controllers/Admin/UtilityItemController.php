<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Carbon\Carbon;
use App\Models\UtilityItem;
use Illuminate\Http\Request;
use App\Constants\AppConstants;
use App\Models\UtilityCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreUtilityItemRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\UpdateUtilityItemRequest;
use App\Http\Requests\MassDestroyUtilityItemRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UtilityItemController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('utility_item_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = UtilityItem::with(['utility_category'])->select(sprintf('%s.*', (new UtilityItem())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'utility_item_show';
                $editGate = 'utility_item_edit';
                $deleteGate = 'utility_item_delete';
                $crudRoutePart = 'utility-items';

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
            $table->addColumn('utility_category_name', function ($row) {
                return $row->utility_category ? $row->utility_category->name : '';
            });

            $table->editColumn('icon_url', function ($row) {
                return $row->icon_url ? $row->icon_url : '';
            });
            $table->editColumn('header', function ($row) {
                return $row->header ? $row->header : '';
            });
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->editColumn('download_file_url', function ($row) {
                return $row->download_file_url ? $row->download_file_url : '';
            });
            $table->editColumn('youtube_embedded_url', function ($row) {
                return $row->youtube_embedded_url ? $row->youtube_embedded_url : '';
            });
            $table->editColumn('youtube_thumbnail_url', function ($row) {
                return $row->youtube_thumbnail_url ? $row->youtube_thumbnail_url : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? UtilityItem::STATUS_SELECT[$row->status] : '';
            });
            $table->editColumn('order_value', function ($row) {
                return $row->order_value ? $row->order_value : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'utility_category']);

            return $table->make(true);
        }

        return view('admin.utilityItems.index');
    }

    public function create()
    {
        abort_if(Gate::denies('utility_item_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $utility_categories = UtilityCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.utilityItems.create', compact('utility_categories'));
    }

    public function store(StoreUtilityItemRequest $request)
    {
        $utilityItem = $request->all();
        if(isset($request->download_file_url) && $request->download_file_url != null ){
            $fileName = $request->file('download_file_url')->getClientOriginalName();
            $path = Storage::disk('utility-files')->putFileAs('utility-files',$request->file('download_file_url'),$fileName, ['visibility' => 'public']);
            $utilityItem['download_file_url'] =env('DO_URL').$path;
        }

        $utilityItem = UtilityItem::create($utilityItem);

        Cache::forget(AppConstants::CACHE_KEY_UTILITY_ITEMS);
        return redirect()->route('admin.utility-items.index');
    }

    public function edit(UtilityItem $utilityItem)
    {
        abort_if(Gate::denies('utility_item_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $utility_categories = UtilityCategory::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $utilityItem->load('utility_category');

        return view('admin.utilityItems.edit', compact('utilityItem', 'utility_categories'));
    }

    public function update(UpdateUtilityItemRequest $request, UtilityItem $utilityItem)
    {
        $utilityItemToUpdate = $request->all();
        if(isset($request->download_file_url) && $request->download_file_url != null ){
            if(!Storage::disk('utility-files')->exists($request->download_file_url)){
                $fileName = $request->file('download_file_url')->getClientOriginalName();
                $path = Storage::disk('utility-files')->putFileAs('utility-files',$request->file('download_file_url'),$fileName, ['visibility' => 'public']);
                $utilityItem['download_file_url'] =env('DO_URL').$path;
            }
        }
        $utilityItem = $utilityItem->update($utilityItemToUpdate);
        Cache::forget(AppConstants::CACHE_KEY_UTILITY_ITEMS);
        return redirect()->route('admin.utility-items.index');
    }

    public function show(UtilityItem $utilityItem)
    {
        abort_if(Gate::denies('utility_item_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $utilityItem->load('utility_category');

        return view('admin.utilityItems.show', compact('utilityItem'));
    }

    public function destroy(UtilityItem $utilityItem)
    {
        abort_if(Gate::denies('utility_item_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $utilityItem->delete();
        Cache::forget(AppConstants::CACHE_KEY_UTILITY_ITEMS);
        return back();
    }

    public function massDestroy(MassDestroyUtilityItemRequest $request)
    {
        UtilityItem::whereIn('id', request('ids'))->delete();
        Cache::forget(AppConstants::CACHE_KEY_UTILITY_ITEMS);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
