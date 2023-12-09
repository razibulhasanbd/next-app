<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\Admin\QuestionResource;
use App\Models\Question;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QuestionApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('question_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new QuestionResource(Question::with(['categories', 'types', 'tags', 'section'])->get());
    }

    public function store(StoreQuestionRequest $request)
    {
        $question = Question::create($request->all());
        $question->categories()->sync($request->input('categories', []));
        $question->types()->sync($request->input('types', []));
        $question->tags()->sync($request->input('tags', []));

        return (new QuestionResource($question))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Question $question)
    {
        abort_if(Gate::denies('question_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new QuestionResource($question->load(['categories', 'types', 'tags', 'section']));
    }

    public function update(UpdateQuestionRequest $request, Question $question)
    {
        $question->update($request->all());
        $question->categories()->sync($request->input('categories', []));
        $question->types()->sync($request->input('types', []));
        $question->tags()->sync($request->input('tags', []));

        return (new QuestionResource($question))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Question $question)
    {
        abort_if(Gate::denies('question_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $question->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
