<?php

namespace App\Http\Controllers;

use DB;
use Throwable;
use Validator;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.     
     *
     * $request includes:
     * String $question
     * Array of Strings $answers[0-9]['content']
     * Boolean $multiplyAnswers
     * Boolean $duplicateAnswers
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate data needed to create a new poll.
        $validator = Validator::make($request->all(), [
            'question' => 'min:1|max:255|string',
            'duplicateAnswer' => 'boolean',
            'multiplyAnswer' => 'boolean',
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
            DB::beginTransaction();
            $question = Question::create([
                'content' => $request['question'],
                'duplicate_answers' => $request['duplicateAnswers'],
                'multiply_answers' => $request['multiplyAnswers'],
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
                'question_id' => $question->id
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
