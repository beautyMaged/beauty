<?php

namespace App\Http\Controllers;

use App\Model\DeliveryCompany;
use Exception;
use Illuminate\Http\Request;
use App\CPU\ImageManager;;
use Illuminate\Support\Facades\Log;

class DeliveryCompanyController extends Controller
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
        $deliveryCompanies = DeliveryCompany::all();
        return response()->json(['deliveryCompanies' => $deliveryCompanies]);

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
            'name' => 'required|string|unique:delivery_companies',
            'logo' => 'required|image|mimes:jpg,jpeg,png,gif',
        ]);

        try {
            // Image Upload
            $logoPath = ImageManager::upload('deliveryCompanies\'Logoes','png',$request->logo);
            // Save DeliveryCompany with logo path
            $deliveryCompany = DeliveryCompany::create([
                'name' => $validatedData['name'],
                'logo' => $logoPath,
            ]);

            return response()->json(['message' => 'DeliveryCompany created successfully', 'deliveryCompany' => $deliveryCompany], 201);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'Internal Server Error'], 500);
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
        // needs Authorization

        $deliveryCompany = DeliveryCompany::find($id);

        if (!$deliveryCompany) {
            return response()->json(['error' => 'DeliveryCompany not found'], 404);
        }

        return response()->json(['deliveryCompany' => $deliveryCompany]);
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
        $deliveryCompany = DeliveryCompany::find($id);

        if (!$deliveryCompany) {
            return response()->json(['error' => 'DeliveryCompany not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif',
        ]);

        try {
            // Update DeliveryCompany name
            $deliveryCompany->update(['name' => $validatedData['name']]);

            // Update logo if provided
            if ($request->hasFile('logo')) {
                // Delete old logo
                ImageManager::delete('deliveryCompanies\'Logoes/'.$deliveryCompany->logo);

                // Upload new logo
                $logoPath = ImageManager::upload('deliveryCompanies\'Logoes','png',$request->logo);
                $deliveryCompany->update(['logo' => $logoPath]);
            }

            return response()->json(['message' => 'DeliveryCompany updated successfully', 'deliveryCompany' => $deliveryCompany]);
        } catch (Exception $e) {
            Log::error($e);
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
        $deliveryCompany = DeliveryCompany::find($id);

        if (!$deliveryCompany) {
            return response()->json(['error' => 'DeliveryCompany not found'], 404);
        }

        try {
            // Delete the logo from storage
            ImageManager::delete('deliveryCompanies\'Logoes/'.$deliveryCompany->logo);

            // Delete the DeliveryCompany
            $deliveryCompany->delete();

            return response()->json(['message' => 'DeliveryCompany deleted successfully']);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
