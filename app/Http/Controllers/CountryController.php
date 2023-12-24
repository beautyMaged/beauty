<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Country;
use Illuminate\Support\Facades\Storage;

class CountryController extends Controller
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
        $countries = Country::all();
        return response()->json(['countries' => $countries]);

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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // needs Authorization
        $validatedData = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string',
            'flag' => 'required|image|mimes:png|max:2048', 
        ]);

        // Store the uploaded img
        $flagPath = $request->file('flag')->store('flags', 'public');

        
        $validatedData['flag'] = $flagPath;

        $country = Country::create($validatedData);

        return response()->json(['message' => 'Country created successfully', 'country' => $country], 201);
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

        $country = Country::find($id);

        if (!$country) {
            return response()->json(['error' => 'Country not found'], 404);
        }

        return response()->json(['country' => $country]);
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
        // needs Authorization

        $country = Country::find($id);

        if (!$country) {
            return response()->json(['error' => 'Country not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string',
        ]);

        $country->update($validatedData);

        return response()->json(['message' => 'Country updated successfully', 'country' => $country]);
    
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

    $country = Country::find($id);

    if (!$country) {
        return response()->json(['error' => 'Country not found'], 404);
    }

    // Delete the flag file from storage
    if ($country->flag) {
        Storage::disk('public')->delete($country->flag);
    }

    $country->delete();

    return response()->json(['message' => 'Country deleted successfully']);
}

}
