<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\CommonQuestion;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CommonQuestionRequest;
use Illuminate\Support\Facades\Validator;
class CommonQuestionController extends Controller
{

    public function __construct(){

        $this->middleware('auth:admin')->except(['index','show','getByType']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $commonQuestions = CommonQuestion::all();
            return response()->json(['data' => $commonQuestions], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommonQuestionRequest $request)
    {
        DB::beginTransaction();
        try {

            $commonQuestion = $request->validated();

            $newCommonQuestion = CommonQuestion::create($commonQuestion);

            DB::commit();
            return response()->json(['data' => $newCommonQuestion], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
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
            $commonQuestion = CommonQuestion::findOrFail($id);

            return response()->json(['data' => $commonQuestion], 200);

        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommonQuestionRequest $request, $id)
    {
        try {
            $commonQuestion = CommonQuestion::findOrFail($id);

            $commonQuestionData = $request->validated();

            $commonQuestion->update($commonQuestionData);

            DB::commit();

            return response()->json(['success' => 'Record updated successfully.'], 200);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $commonQuestion = CommonQuestion::findOrFail($id);

            $commonQuestion->delete();

            DB::commit();
            return response()->json(['success' => 'Record deleted successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getByType($type, $user, Request $request){
        try {

            $validator = Validator::make(compact('type', 'user'), [
                'type' => 'required|in:orders,delivery,payment,refund,products,general',
                'user' => 'required|in:sellers,customers',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'error with query parameters',
                    'errors' => $validator->errors()->toArray()
                ], 422);
            }

            $perPage = $request->query('per_page', 10);


            $commonQuestions = CommonQuestion::where('type', $type)
                ->where('for', $user)
                ->paginate($perPage);

            // if ($commonQuestions->isEmpty()) {
            //     return response()->json(['message' => 'No common questions found '], 404);
            // }

            $data = [
                'data' => $commonQuestions->items(),
                'current_page' => $commonQuestions->currentPage(),
                'total_pages' => $commonQuestions->lastPage(),
                'per_page' => $perPage,
            ];

            return response()->json($data, 200);


        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
