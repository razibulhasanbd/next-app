<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Tag;
use App\Models\Type;
use App\Models\Section;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyQuestionRequest;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class QuestionController extends Controller
{
    use MediaUploadingTrait;
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('question_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Question::with(['categories', 'types', 'tags', 'section'])->select(sprintf('%s.*', (new Question())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'question_show';
                $editGate = 'question_edit';
                $deleteGate = 'question_delete';
                $crudRoutePart = 'questions';

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
            $table->editColumn('question', function ($row) {
                return $row->question ? $row->question : '';
            });
            $table->editColumn('category', function ($row) {
                $labels = [];
                foreach ($row->categories as $category) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $category->name);
                }

                return implode(' ', $labels);
            });
            $table->editColumn('type', function ($row) {
                $labels = [];
                foreach ($row->types as $type) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $type->name);
                }

                return implode(' ', $labels);
            });
            $table->editColumn('tag', function ($row) {
                $labels = [];
                foreach ($row->tags as $tag) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $tag->name);
                }

                return implode(' ', $labels);
            });
            $table->addColumn('section_name', function ($row) {
                return $row->section ? $row->section->name : '';
            });
            $table->addColumn('created_at', function ($row) {
                return $row->section ? frontEndTimeConverterView($row->created_at) : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'category', 'type', 'tag', 'section']);

            return $table->make(true);
        }

        $categories = Category::get();
        $types      = Type::get();
        $tags       = Tag::get();
        $sections   = Section::get();

        return view('admin.questions.index', compact('categories', 'types', 'tags', 'sections'));
    }

    public function create()
    {
        abort_if(Gate::denies('question_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::pluck('name', 'id');

        $types = Type::pluck('name', 'id');

        $tags = Tag::pluck('name', 'id');

        $sections = Section::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.questions.create', compact('categories', 'sections', 'tags', 'types'));
    }

    public function store(StoreQuestionRequest $request)
    {
        $question = Question::create($request->all());
        $question->categories()->sync($request->input('categories', []));
        $question->types()->sync($request->input('types', []));
        $question->tags()->sync($request->input('tags', []));
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $question->id]);
        }
        Helper::forgetFaqCache();
        return redirect()->route('admin.questions.index');
    }

    public function edit(Question $question)
    {
        abort_if(Gate::denies('question_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::pluck('name', 'id');

        $types = Type::pluck('name', 'id');

        $tags = Tag::pluck('name', 'id');

        $sections = Section::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $question->load('categories', 'types', 'tags', 'section');

        return view('admin.questions.edit', compact('categories', 'question', 'sections', 'tags', 'types'));
    }

    public function update(UpdateQuestionRequest $request, Question $question)
    {
        $question->update($request->all());
        $question->categories()->sync($request->input('categories', []));
        $question->types()->sync($request->input('types', []));
        $question->tags()->sync($request->input('tags', []));

        return redirect()->route('admin.questions.index');
    }

    public function show(Question $question)
    {
        abort_if(Gate::denies('question_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $question->load('categories', 'types', 'tags', 'section');

        return view('admin.questions.show', compact('question'));
    }

    public function destroy(Question $question)
    {
        abort_if(Gate::denies('question_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $question->delete();

        return back();
    }

    public function massDestroy(MassDestroyQuestionRequest $request)
    {
        Question::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('question_create') && Gate::denies('question_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Question();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }


    public function get_selected_question(Request $request)
    {

        $tag = $request->tag;
        $type = $request->type;


        // filtering type tag and category

        if ($request->category) {

            if (($tag == null) && ($type == null)) {
                $get_all_faq = Question::select('questions.id', 'questions.question', 'questions.answer', 'questions.created_at')->whereHas('categories', function ($query) use ($request) {
                    $query->whereIn('name', $request->category);
                })->with(['tags', 'types'])->orderBy('questions.created_at')->get();
            } else if ($type == null) {
                $get_all_faq = Question::select('questions.id', 'questions.question', 'questions.answer', 'questions.created_at')->whereHas('categories', function ($query) use ($request) {
                    $query->whereIn('name', $request->category);
                })->WhereHas('tags', function ($qu) use ($request) {
                    $qu->whereIn('name', $request->tag);
                })->with('tags', function ($y) use ($request) {
                    $y->select('name', 'description')->whereIn('name', $request->tag);;
                })->with('types')->orderBy('questions.created_at')->get();
            } else if ($tag == null) {
                $get_all_faq = Question::select('questions.id', 'questions.question', 'questions.answer', 'questions.created_at')->whereHas('categories', function ($query) use ($request) {
                    $query->whereIn('name', $request->category);
                })->WhereHas('types', function ($q) use ($request) {
                    $q->whereIn('name', $request->type);
                })->with('types', function ($y) use ($request) {
                    $y->select('name', 'description')->whereIn('name', $request->type);;
                })->with('tags')->orderBy('questions.created_at')->get();
            } else {
                $get_all_faq = Question::select('questions.id', 'questions.question', 'questions.answer', 'questions.created_at')->whereHas('categories', function ($query) use ($request) {
                    $query->whereIn('name', $request->category);
                })->WhereHas('tags', function ($qu) use ($request) {
                    $qu->whereIn('name', $request->tag);
                })->WhereHas('types', function ($q) use ($request) {
                    $q->whereIn('name', $request->type);
                })->with('tags', function ($y) use ($request) {
                    $y->select('name', 'description')->whereIn('name', $request->tag);;
                })->with('types', function ($y) use ($request) {
                    $y->select('name', 'description')->whereIn('name', $request->type);;
                })->orderBy('questions.created_at')->get();
            }
        } else {
            return response("category is not given");
        }
        // return $get_all_faq;
        //set key of tag and type according to output
        $output = [];

        foreach ($get_all_faq as $res) {

            foreach ($res->types as $next) {

                //  echo "aaa";

                foreach ($res->tags as $key) {

                    $type_id = $next->pivot["type_id"];
                    $tag_id = $key->pivot["tag_id"];

                    $resp = Tag::where('id', $tag_id)->where('type_id', $type_id)->count();

                    if ($resp >= 1)
                        $output[$next->description][$key->description][] = $res;
                }
            }
        }
        return $output;
    }
}
