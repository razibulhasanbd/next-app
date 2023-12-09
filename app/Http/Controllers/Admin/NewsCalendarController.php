<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyNewsCalendarRequest;
use App\Http\Requests\StoreNewsCalendarRequest;
use App\Http\Requests\UpdateNewsCalendarRequest;
use App\Models\NewsCalendar;
use App\Traits\Auditable;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class NewsCalendarController extends Controller
{
    use CsvImportTrait;
    use Auditable;

    public function index(Request $request)
    {
        abort_if(Gate::denies('news_calendar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = NewsCalendar::query()->select(sprintf('%s.*', (new NewsCalendar())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'news_calendar_show';
                $editGate = 'news_calendar_edit';
                $deleteGate = 'news_calendar_delete';
                $crudRoutePart = 'news-calendars';

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
            $table->editColumn('title', function ($row) {
                if ($row->is_restricted == 1) {
                    return $row->title  ? $row->title . ' ' . '<a href="' . route('admin.news.unRestrictNews', $row->id) . '">' . '<span class="badge badge-success">' . 'Unrestrict' . '</span>' . '</a>' : '';
                } else {
                    return $row->title ? $row->title : '';
                }
            });
            $table->editColumn('country', function ($row) {
                return $row->country ? $row->country : '';
            });

            $table->editColumn('impact', function ($row) {
                return $row->impact ? $row->impact : '';
            });
            $table->editColumn('forecast', function ($row) {
                return $row->forecast ? $row->forecast : '';
            });
            $table->editColumn('previous', function ($row) {
                return $row->previous ? $row->previous : '';
            });
            $table->editColumn('date', function ($row) {
                return $row->date ? frontEndTimeConverterView($row->date) : '';
            });
            $table->editColumn('is_restricted', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->is_restricted ? 'checked' : null) . '>';
            });

            $table->rawColumns(['actions', 'title', 'placeholder', 'is_restricted']);

            return $table->make(true);
        }

        return view('admin.newsCalendars.index');
    }

    public function create()
    {
        abort_if(Gate::denies('news_calendar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.newsCalendars.create');
    }

    public function store(StoreNewsCalendarRequest $request)
    {
        $newsCalendar = NewsCalendar::create($request->all());

        return redirect()->route('admin.news-calendars.index');
    }

    public function edit(NewsCalendar $newsCalendar)
    {
        abort_if(Gate::denies('news_calendar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.newsCalendars.edit', compact('newsCalendar'));
    }

    public function update(UpdateNewsCalendarRequest $request, NewsCalendar $newsCalendar)
    {
        $newsCalendar->update($request->all());

        return redirect()->route('admin.news-calendars.index');
    }

    public function show(NewsCalendar $newsCalendar)
    {
        abort_if(Gate::denies('news_calendar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.newsCalendars.show', compact('newsCalendar'));
    }

    public function destroy(NewsCalendar $newsCalendar)
    {
        abort_if(Gate::denies('news_calendar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $newsCalendar->delete();

        return back();
    }

    public function massDestroy(MassDestroyNewsCalendarRequest $request)
    {
        NewsCalendar::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massRestricted()
    {
        NewsCalendar::whereIn('id', request('ids'))->update(['is_restricted' => 1]);

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function unRestrictNews($id)
    {
        NewsCalendar::find($id)->update(['is_restricted' => 0]);
        return redirect()->back();
    }

    public function massUnRestricted()
    {
        $id = implode(",",request('ids'));
        $newsCalendar =  NewsCalendar::whereIn('id', request('ids'))->update(['is_restricted' => 0]);
        $model = array(
            'properties'=>array('login' =>'App\Models\NewsCalendar#1569','id'=> $id),
        );
        $model = json_encode($model);
        $model = json_decode($model);
        $this->audit("news:bulkUnRestrict", $model);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
