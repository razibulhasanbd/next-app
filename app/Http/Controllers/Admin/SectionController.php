<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySectionRequest;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use App\Models\Section;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('section_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Section::query()->select(sprintf('%s.*', (new Section())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'section_show';
                $editGate = 'section_edit';
                $deleteGate = 'section_delete';
                $crudRoutePart = 'sections';

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

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.sections.index');
    }

    public function create()
    {
        abort_if(Gate::denies('section_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sections.create');
    }

    public function store(StoreSectionRequest $request)
    {
        $section = Section::create($request->all());

        return redirect()->route('admin.sections.index');
    }

    public function edit(Section $section)
    {
        abort_if(Gate::denies('section_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sections.edit', compact('section'));
    }

    public function update(UpdateSectionRequest $request, Section $section)
    {
        $section->update($request->all());
        Helper::forgetFaqCache();
        return redirect()->route('admin.sections.index');
    }

    public function show(Section $section)
    {
        abort_if(Gate::denies('section_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.sections.show', compact('section'));
    }

    public function destroy(Section $section)
    {
        abort_if(Gate::denies('section_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $section->delete();
        Helper::forgetFaqCache();
        return back();
    }

    public function massDestroy(MassDestroySectionRequest $request)
    {
        Section::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
