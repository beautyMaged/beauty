<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Policy;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PolicyController extends Controller
{

    function __construct()
    {
        $this->middleware('auth:admin')->except(['storeForSeller','getPoliciesToSeller']);
        $this->middleware('auth:seller')->only(['storeForSeller']);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $policies = Policy::all();
            return response()->json(['data' => $policies]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    // get all policies for a seller

    public function getPoliciesToSeller(){
        try {
            $policies = Policy::where('is_approved','1')->where('is_global','1')->get();
            return response()->json(['data' => $policies]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error','message'=>$e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|unique:policies',
            'description' => 'required|string',
            'type' => [
                'required',
                Rule::in(['refund', 'delivery', 'common_questions', 'copyright', 'product_view', 'other'])
            ],
        ]);
        $request['is_approved'] = true;
        $request['is_global'] = true;

        $policyData = $request->only(['name', 'description', 'type','is_approved','is_global']);
        
        try {
            DB::beginTransaction();

            $policy = Policy::create($policyData);

            DB::commit();

            return response()->json(['data' => $policy]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'database error', 'error' => $e->getMessage()], 500);
        }
    }

    public function storeForSeller(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|unique:policies',
            'description' => 'required|string',
            'type' => [
                'required',
                Rule::in(['refund', 'delivery', 'common_questions', 'copyright', 'product_view', 'other'])
            ],
        ]);

        $policyData = $request->only(['name', 'description', 'type']);
        
        try {
            DB::beginTransaction();

            $policy = Policy::create($policyData);

            DB::commit();

            return response()->json(['data' => $policy]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'database error', 'error' => $e->getMessage()], 500);
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
            $policy = Policy::findOrFail($id);
            return response()->json(['data' => $policy]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Policy not found'], 404);
        }
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
        
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'is_approved' => 'required|boolean',
            'is_global' => 'required|boolean',
            'type' => [
                'required',
                Rule::in(['refund', 'delivery', 'common_questions', 'copyright', 'product_view', 'other'])
            ],
        ]);

        try {

            DB::beginTransaction();

            $policy = Policy::findOrFail($id);
            $policy->update($request->only(['name', 'description', 'type','is_approved','is_global']));

            DB::commit();

            return response()->json(['data' => $policy]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Internal Server Error'], 500);
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
        try {


            $policy = Policy::findOrFail($id);

            if(!$policy->sellers->isEmpty()) {
                return response()->json(['error' => 'Policy belongs to a seller'], 500);
            }

            DB::beginTransaction();

            $policy->delete();

            DB::commit();

            return response()->json(['message' => 'Policy deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Policy not found'], 404);
        }
    }
}
