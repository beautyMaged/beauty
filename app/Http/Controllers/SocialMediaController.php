<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\SocialMedia;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\CPU\ImageManager;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except(['show', 'index']);
    }

    public function index()
    {
        try {
            $socialMedia = SocialMedia::all();

            $socialMedia = $socialMedia->map(function ($item) {
                $item['icon'] = asset('storage/socialMediaIcons/' . $item['icon']);
                return $item;
            });

            return response()->json(['data' => $socialMedia], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function show($id)
    {
        try {
            $socialMedia = SocialMedia::findOrFail($id);

            $socialMedia['icon'] = asset('storage/socialMediaIcons/' . $socialMedia['icon']);

            return response()->json(['data' => $socialMedia], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Social Media not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'link' => 'required|url',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $imageName = ImageManager::upload('socialMediaIcons/','PNG',$request->icon );

            $socialMedia = new SocialMedia();
            $socialMedia->name = $request->input('name');
            $socialMedia->link = $request->input('link');
            $socialMedia->icon = $imageName;



            $socialMedia->save();

            DB::commit();

            return response()->json(['data' => $socialMedia], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create social media'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'link' => 'required|url',
            'icon' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $socialMedia = SocialMedia::findOrFail($id);
            $socialMedia->name = $request->input('name');
            $socialMedia->link = $request->input('link');

            // Update the icon image if provided
            if ($request->hasFile('icon')) {
                $icon = $request->file('icon');
                $iconPath = $icon->store('social_media_icons', 'public');
                $socialMedia->icon = $iconPath;
            }

            $socialMedia->save();

            DB::commit();

            return response()->json(['data' => $socialMedia], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update social media'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
    
            $socialMedia = SocialMedia::findOrFail($id);
    
            $imagePath = 'socialMediaIcons/' . $socialMedia->icon;
            ImageManager::delete($imagePath);
    
            $socialMedia->delete();
    
            DB::commit();
    
            return response()->json(['message' => 'Social Media deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete social media'], 500);
        }
    }
    
}
