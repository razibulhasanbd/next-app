<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTagRequest;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use App\Models\Type;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class TagController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('tag_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Tag::with(['type'])->select(sprintf('%s.*', (new Tag())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'tag_show';
                $editGate = 'tag_edit';
                $deleteGate = 'tag_delete';
                $crudRoutePart = 'tags';

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
            $table->editColumn('description', function ($row) {
                return $row->description ? $row->description : '';
            });
            $table->addColumn('type_name', function ($row) {
                return $row->type ? $row->type->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'type']);

            return $table->make(true);
        }

        $types = Type::get();

        return view('admin.tags.index', compact('types'));
    }

    public function create()
    {
        abort_if(Gate::denies('tag_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $types = Type::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.tags.create', compact('types'));
    }

    public function store(StoreTagRequest $request)
    {
        $tag = Tag::create($request->all());

        return redirect()->route('admin.tags.index');
    }

    public function edit(Tag $tag)
    {
        abort_if(Gate::denies('tag_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $types = Type::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tag->load('type');

        return view('admin.tags.edit', compact('tag', 'types'));
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $tag->update($request->all());
        Helper::forgetFaqCache();
        return redirect()->route('admin.tags.index');
    }

    public function show(Tag $tag)
    {
        abort_if(Gate::denies('tag_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tag->load('type');

        return view('admin.tags.show', compact('tag'));
    }

    public function destroy(Tag $tag)
    {
        abort_if(Gate::denies('tag_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tag->delete();
        Helper::forgetFaqCache();
        return back();
    }

    public function massDestroy(MassDestroyTagRequest $request)
    {
        Tag::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
