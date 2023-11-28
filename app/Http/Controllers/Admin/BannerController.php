<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\homeBannerSettingUpdateRequest;
use App\Model\Banner;
use App\Model\HomeBannerSetting;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $banners = Banner::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('banner_type', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        } else {
            $banners = Banner::orderBy('id', 'desc');
        }
        $banners = $banners->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.banner.view', compact('banners', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required',
            'image' => 'required',
        ], [
            'url.required' => 'url is required!',
            'image.required' => 'Image is required!',

        ]);
        //return $request;
        $banner = new Banner;
        $banner->main_title = $request->main_title;
        $banner->title = $request->title;
        $banner->description = $request->description;
        $banner->banner_type = $request->banner_type;
        $banner->resource_type = $request->resource_type;
        $banner->resource_id = $request[$request->resource_type . '_id'];
        $banner->url = $request->url;
        $banner->photo = ImageManager::upload('banner/', 'png', $request->file('image'));
        $banner->save();
        Toastr::success('Banner added successfully!');
        return back();
    }

    public function status(Request $request)
    {
        if ($request->ajax()) {
            $banner = Banner::find($request->id);
            $banner->published = $request->status;
            $banner->save();
            $data = $request->status;
            return response()->json($data);
        }
    }

    public function edit($id)
    {
        $banner = Banner::where('id', $id)->first();
        return view('admin-views.banner.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'url' => 'required',
        ], [
            'url.required' => 'url is required!',
        ]);

        $banner = Banner::find($id);
        $banner->banner_type = $request->banner_type;
        $banner->resource_type = $request->resource_type;
        $banner->resource_id = $request[$request->resource_type . '_id'];
        $banner->url = $request->url;
        if ($request->file('image')) {
            $banner->photo = ImageManager::update('banner/', $banner['photo'], 'png', $request->file('image'));
        }
        $banner->save();

        Toastr::success('Banner updated successfully!');
        return back();
    }

    public function delete(Request $request)
    {
        $br = Banner::find($request->id);
        ImageManager::delete('/banner/' . $br['photo']);
        Banner::where('id', $request->id)->delete();
        return response()->json();
    }


    public function homeBannerSetting()
    {
        return view('admin-views.home-banner-settings.view');
    }

    public function homeBannerSettingEdit()
    {
        $settings_of_banner = HomeBannerSetting::first();
        return view('admin-views.home-banner-settings.settings-edit', compact('settings_of_banner'));
    }

    public function homeBannerSettingUpdate(homeBannerSettingUpdateRequest $request)
    {
        //        return $request->description_o[array_search('sa', $request->lang)];
        //return $request;
        $settings_of_banner = HomeBannerSetting::first();

        if ($request->hasFile('image_o')) {
            $request->image_o->store('/', 'banners_home');
            $filename = $request->image_o->hashName();
            $settings_of_banner->image_o = $filename;
        }
        if ($request->hasFile('image_t')) {
            $request->image_t->store('/', 'banners_home');
            $filename = $request->image_t->hashName();
            $settings_of_banner->image_t = $filename;
        }

        if ($request->hasFile('image_t')) {
            $request->image_t->store('/', 'banners_home');
            $filename = $request->image_t->hashName();
            $settings_of_banner->image_t = $filename;
        }

        foreach ($request->lang as $index => $key) {
            if ($request->title_o[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    [
                        'translationable_type' => 'App\Model\HomeBannerSetting',
                        'translationable_id' => $settings_of_banner->id,
                        'locale' => $key,
                        'key' => 'title_o'
                    ],
                    ['value' => $request->title_o[$index]]
                );
            }
            if ($request->title_t[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    [
                        'translationable_type' => 'App\Model\HomeBannerSetting',
                        'translationable_id' => $settings_of_banner->id,
                        'locale' => $key,
                        'key' => 'title_t'
                    ],
                    ['value' => $request->title_t[$index]]
                );
            }
            if ($request->description_o[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    [
                        'translationable_type' => 'App\Model\HomeBannerSetting',
                        'translationable_id' => $settings_of_banner->id,
                        'locale' => $key,
                        'key' => 'description_o'
                    ],
                    ['value' => $request->description_o[$index]]
                );
            }
            if ($request->description_o[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    [
                        'translationable_type' => 'App\Model\HomeBannerSetting',
                        'translationable_id' => $settings_of_banner->id,
                        'locale' => $key,
                        'key' => 'description_t'
                    ],
                    ['value' => $request->description_t[$index]]
                );
            }
        }
        $settings_of_banner->save();
        Toastr::success('Banner Details updated successfully.');
        return back();
    }
}
