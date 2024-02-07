<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\City;
use Illuminate\Support\Facades\Storage;

class CityController extends Controller
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
        $cities = City::all();
        return response()->json(['cities' => $cities]);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // needs Authorization
        $validatedData = $request->validate([
            'name' => 'required|string',
            'country_id'=>'required|integer|exists:countries,id'
        ]);

        $city = City::create($validatedData);

        return response()->json(['message' => 'City created successfully', 'city' => $city], 201);
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

        $city = City::find($id);

        if (!$city) {
            return response()->json(['error' => 'City not found'], 404);
        }

        return response()->json(['city' => $city]);
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
        // needs Authorization

        $city = City::find($id);

        if (!$city) {
            return response()->json(['error' => 'City not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string',
            'country_id'=>'required|integer|exists:countries,id'
        ]);

        $city->update($validatedData);

        return response()->json(['message' => 'City updated successfully', 'city' => $city]);
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
{
    // needs Authorization

    $city = City::find($id);

    if (!$city) {
        return response()->json(['error' => 'City not found'], 404);
    }


    $city->delete();

    return response()->json(['message' => 'City deleted successfully']);
}

}
