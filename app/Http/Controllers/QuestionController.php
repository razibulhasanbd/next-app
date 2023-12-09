<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class QuestionController extends Controller
{

    public function show(Request $request)
    {





        $questions = [];
        







        $questions = [];

        foreach ($request->type as $type) {
            foreach ($request->tags as $tag) {

                // return $tag;
                $questions[$type] = Question::whereHas('categories', function ($query) use ($request) {
                    if (!empty($request->category)) {
                        $query->whereName($request->category);
                    }
                })->whereHas('types', function ($query) use ($request, $type) {
                    if (!empty($request->type)) {
                        $query->whereName($type);
                    }
                })->whereHas('tags', function ($query) use ($request, $tag) {
                    if (!empty($request->tags)) {
                        $query->whereName($tag);
                    }
                })->with(['types', 'categories'])->get();
            }
        }
        return $questions;
        foreach ($questions as &$types) {

            // return $types;

            foreach ($types as &$type) {

                return $type;



                foreach ($type->tags as $tag) {


                    //    $new


                }
            }
        }

        return $questions;








        $get_all_questions = Question::whereHas('categories', function ($query) use ($request) {
            if (!empty($request->category)) {
                $query->whereName($request->category);
            }
        })->whereHas('types', function ($query) use ($request) {
            if (!empty($request->type)) {
                $query->whereIn('name', $request->type);
            }
        })->whereHas('tags', function ($query) use ($request) {
            if (!empty($request->tags)) {
                $query->whereIn('name', $request->tags);
            }
        })->with(['tags', 'types', 'categories'])->get();
        // return $get_all_questions;


        $get_all_questions->transform(function ($q) {
            // return $q;
            // return  $q->tags = $q->tags->pluck('name');
            $q['tag'] = $q->tags->pluck('name');
            $q['type'] = $q->types->pluck('name');
            $q['category'] = $q->categories->pluck('name');
            $q->unsetRelation('tags');
            $q->unsetRelation('categories');
            $q->unsetRelation('types');
            return $q;
        });

        return $get_all_questions;


        $get_all_questions->transform(function ($q) {
            // return $q;
            // return  $q->tags = $q->tags->pluck('name');
            $q['tag'] = $q->tags->pluck('name');
            $q['type'] = $q->types->pluck('name');
            $q['category'] = $q->categories->pluck('name');
            $q->unsetRelation('tags');
            $q->unsetRelation('categories');
            $q->unsetRelation('types');
            return $q;
        });



        $requestTypes = collect($request->type);







        $c = $requestTypes->each(function ($r) use ($get_all_questions) {





            $a = $get_all_questions->each(function ($q) use ($r) {


                $isMatched = in_array($r, $q->type->toArray());

                if ($isMatched) {

                    $a[$r][] = $q->toArray();

                    // dd($a);
                    return $a;
                }
            });
            dd($a);
            return $a;
        });


        return $c->all();
        $filtered = $get_all_questions->whereIn('type', $request->type);

        return  $filtered->all();


        return  $get_all_questions;
        $group = $get_all_questions->groupBy(function ($question) {


            return $question->map(function ($q) {
                return  $q->name;
            });
        });

        return $group;
    }
}
