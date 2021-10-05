<?php

namespace App\Http\Controllers;

use DB;
use Throwable;
use Validator;
use App\Models\Question;
use App\Models\Result;
use Illuminate\Http\JsonResponse;
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
     * @param  int  $id
     * @var  int|int[] $request['result']
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, int $id): JsonResponse
    {
        //Save answering user IP.
        $userIp = $request->getClientIp();

        try {
            //Get poll with answers
            $poll = Question::with('answers')->where('id', $id)->firstOrFail();
            //Count amount of answers
            $answersAmount = count($poll->answers);

            /**
             * Search if poll can be answered only once by same user.
             * When true check if user IP is in database.
             */
            $searchDuplicates = null;
            if (!$poll->duplicate_answers) {
                $searchDuplicates = DB::table('results')
                    ->join('answers', 'answers.id', '=', 'results.answer_id')
                    ->join('questions', 'questions.id', '=', 'answers.question_id')
                    ->select('results.user')
                    ->where('questions.id', '=', $id)
                    ->where('results.user', '=', $userIp)
                    ->first();
            }

            //If poll can be answered only once and user from this IP answered don't store a new resource.
            if (!$poll->duplicate_answers && !is_null($searchDuplicates)) {
                return response()->json([
                    'message' => 'You already answered to this poll.'
                ], 400);
            }

            //Validate if poll accept many answers. 
            if ($poll->many_answers) {
                $validator = Validator::make($request->all(), [
                    'result' => "array|min:1|max:$answersAmount",
                ]);
            } //Validate if poll accept single answer.
            else {
                $validator = Validator::make($request->all(), [
                    'result' => 'integer',
                ]);
            }

            //Trigger when validation fails for any reason.
            if ($validator->fails()) {
                return response()->json([
                    'message' => $validator->errors(),
                ], 400);
            }

            //Store many answers from single poll.
            if (is_array($request['result'])) {
                DB::beginTransaction();
                foreach ($request['result'] as $result) {
                    Result::create([
                        'answer_id' => $result,
                        'user' => $poll['duplicate_answers'] ? null : $request->getClientIp()
                    ]);
                }
                DB::commit();
            } //Store single answer from single poll. 
            else {
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            //Get the poll resource.
            $poll = Question::where('id', $id)->first();

            //Count Answers            
            $results = DB::table('questions')
                ->leftJoin('answers', 'questions.id', '=', 'answers.question_id')
                ->leftJoin('results', 'answers.id', '=', 'results.answer_id')
                ->select(array('answers.id', 'answers.content', DB::raw('COUNT(results.answer_id) AS resultCount')))
                ->where('questions.id', '=', $id)
                ->groupBy('answers.id')
                ->get();

            return response()->json([
                'message' => 'Poll has been loaded successfully.',
                'pollResults' => $results,
                'question' => $poll
            ], 200);
        } catch (Throwable $throwable) {
            return response()->json([
                'message' => $throwable
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
