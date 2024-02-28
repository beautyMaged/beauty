<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\District;
class DistrictController extends Controller
{
    public function __construct() {
        $this->middleware('auth:admin')->only('store', 'update', 'destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $districts = District::all();
        return response()->json(['districts' => $districts]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'city_id'=>'required|integer|exists:cities,id'
        ]);
        $district = District::create($validatedData);

        return response()->json(['message' => 'District created successfully', 'district' => $district], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // needs Authorization

        $district = District::find($id);

        if (!$district) {
            return response()->json(['error' => 'District not found'], 404);
        }

        return response()->json(['district' => $district]);
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
        $district = District::find($id);

        if (!$district) {
            return response()->json(['error' => 'District not found'], 404);
        }
        $validatedData = $request->validate([
            'name' => 'required|string',
            'city_id'=>'required|integer|exists:cities,id'
        ]);

        $district->update($validatedData);

        return response()->json(['message' => 'District updated successfully', 'district' => $district]);
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $district = District::find($id);

        if (!$district) {
            return response()->json(['error' => 'District not found'], 404);
        }
    
    
        $district->delete();
    
        return response()->json(['message' => 'District deleted successfully']);
    }
}
