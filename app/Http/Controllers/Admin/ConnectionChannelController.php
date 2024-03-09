<?php

namespace App\Http\Controllers\Admin;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ConnectionChannel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ConnectionChannelRequest;

class ConnectionChannelController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin')->except(['index','show']);
    }
    public function index()
    {
        try {
            $connectionChannels = ConnectionChannel::all();
            return response()->json(['data' => $connectionChannels], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(ConnectionChannelRequest $request)
    {
        DB::beginTransaction();
        try {
            $logoPath = ImageManager::upload('connection-channels', 'png', $request->file('logo'));
            $connectionChannelData = $request->validated();
            $connectionChannelData['logo'] = $logoPath;

            $connectionChannel = ConnectionChannel::create($connectionChannelData);

            DB::commit();
            return response()->json(['data' => $connectionChannel], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $connectionChannel = ConnectionChannel::findOrFail($id);
            return response()->json(['data' => $connectionChannel], 200);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }

    public function update(ConnectionChannelRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $connectionChannel = ConnectionChannel::findOrFail($id);

            $logoPath = ImageManager::upload('connection-channels', 'png', $request->file('logo'));
            $connectionChannelData = $request->validated();
            $connectionChannelData['logo'] = $logoPath;

            $oldImagePath = $connectionChannel->logo;
            ImageManager::update('connection-channels', $oldImagePath, 'png', $request->file('logo'));
            $connectionChannel->update($connectionChannelData);

            DB::commit();
            return response()->json(['success' => 'Record updated successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $connectionChannel = ConnectionChannel::findOrFail($id);
            ImageManager::delete('connection-channels/' . $connectionChannel->logo);
            $connectionChannel->delete();

            DB::commit();
            return response()->json(['success' => 'Record deleted successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
