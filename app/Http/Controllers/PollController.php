<?php

namespace App\Http\Controllers;

use DB;
use Throwable;
use Validator;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(null, 501);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response(null, 501);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @var String $request['question']
     * @var Boolean $request['duplicateAnswer']
     * @var Boolean $request['manyAnswer']
     * @var String $request['answers'][0-9]['content']
     *
     * @return \Illuminate\Http\JsonResponse     
     */
    public function store(Request $request): JsonResponse
    {
        //Validate data needed to create a new resource.
        $validator = Validator::make($request->all(), [
            'question' => 'min:1|max:255|string',
            'duplicateAnswer' => 'boolean',
            'manyAnswer' => 'boolean',
            'answers' => 'min:2|max:10',
            'answers.*' => 'array:content',
            'answers.*.content' => 'min:1|max:255|string',
        ]);

        //Trigger when validation fails for any reason.
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }

        //Try to storege a new resource.
        try {
            $question = null;

            //Try to store a question and all naswers in database
            DB::beginTransaction();
            $question = Question::create([
                'content' => $request['question'],
                'duplicate_answers' => $request['duplicateAnswers'],
                'many_answers' => $request['manyAnswers'],
            ]);
            foreach ($request['answers'] as $answer) {
                Answer::create([
                    'question_id' => $question->id,
                    'content' => $answer['content']
                ]);
            }
            DB::commit();

            return response()->json([
                'message' => 'Poll is created',
                'questionId' => $question->id //Required to
            ], 200);
        } catch (Throwable $throwable) {
            DB::rollBack();
            return response()->json([
                'message' => $throwable
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            //Get the specified poll or fail if it doesn't exist.
            $poll = Question::with('answers')->where('id', $id)->firstOrFail();

            return response()->json([
                'message' => 'Poll has been successfully loaded.',
                'poll' => $poll,
            ], 200);
        } catch (Throwable $throwable) {
            return response()->json([
                'message' => $throwable,
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response(null, 501);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return response(null, 501);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response(null, 501);
    }
}
