<?php

namespace App\Http\Controllers;

use DB;
use Throwable;
use Validator;
use App\Models\Question;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $userIp = $request->getClientIp();
        $searchDuplicates = null;

        try {
            $poll = Question::with('answers')->where('id', $id)->firstOrFail();
            $answersAmount = count($poll->answers);

            if (!$poll->duplicate_answers) {
                $searchDuplicates = DB::table('results')
                    ->join('answers', 'answers.id', '=', 'results.answer_id')
                    ->join('questions', 'questions.id', '=', 'answers.question_id')
                    ->select('results.user')
                    ->where('questions.id', '=', $id)
                    ->where('results.user', '=', $userIp)
                    ->first();
            }

            if (!$poll->duplicate_answers && !is_null($searchDuplicates)) {
                return response()->json([
                    'message' => 'You already answered to this poll.'
                ], 400);
            }

            if ($poll->many_answers) {
                $validator = Validator::make($request->all(), [
                    'result' => "array|min:1|max:$answersAmount",
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'result' => 'integer',
                ]);
            }

            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }

            if (is_array($request['result'])) {
                DB::beginTransaction();
                foreach ($request['result'] as $result) {
                    Result::create([
                        'answer_id' => $result,
                        'user' => $poll['duplicate_answers'] ? null : $request->getClientIp()
                    ]);
                }
                DB::commit();
            } else {
                Result::create([
                    'answer_id' => $request['result'],
                    'user' => $poll['duplicate_answers'] ? null : $request->getClientIp(),
                ]);
            }

            return response()->json([
                'message' => 'Result saved.',
            ], 200);
        } catch (Throwable $throwable) {
            DB::rollBack();
            return response()->json([
                'message' => $throwable,
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
        try {
            $results = DB::table('results')
                ->join('answers', 'answers.id', '=', 'results.answer_id')
                ->join('questions', 'questions.id', '=', 'answers.question_id')
                ->select(array('results.answer_id AS id','answers.content', DB::raw('COUNT(results.answer_id) AS resultCount')))
                ->where('questions.id', '=', $id)
                ->groupBy('results.answer_id')
                ->get();
            
            $poll = Question::where('id', $id)->first();

            return response()->json([
                'pollResults' => $results,
                'question' => $poll->content
            ],200);
        } catch (Throwable $throwable) {
            return response()->json([
                'message' => $throwable
            ],500);
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
