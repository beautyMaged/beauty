<?php

namespace App\Http\Controllers\Seller;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\homeBannerSettingUpdateRequest;
use App\Model\Banner;
use App\Model\Seller;
use App\Model\HomeBannerSetting;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Requests\Banner\StoreRequest;
use App\Http\Requests\Banner\UpdateRequest;
use Carbon\Carbon;

class BannerController extends Controller
{
    function list(Request $request)
    {
        /** @var Seller $seller */
        $seller = auth()->user();
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $banners = $seller->banners()->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('title', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        } else
            $banners = $seller->banners()->orderBy('id', 'desc');
        $banners = $banners->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('seller-views.banner.view', compact('banners', 'search'));
    }

    public function store(StoreRequest $request)
    {
        $banner = new Banner;
        $banner->seller_id = auth()->user()->id;
        $banner->title = $request->title;
        $banner->description = $request->description;
        $banner->banner_type = $request->{$request->resource_type . '_banner_position'};
        $banner->target = $request->target_type;
        $banner->start_at = Carbon::parse($request->start_at)->format('Y-m-d\TH:i');
        $banner->end_at = Carbon::parse($request->end_at)->format('Y-m-d\TH:i');
        $banner->photo = ImageManager::upload('banner/', 'png', $request->file('image'));
        switch ($request->resource_type) {
            case 'category':
                $banner->category_id = $request->category_id;
                break;
                // brand, product, shop
            default:
                $banner->category_id = null;
        }
        $banner->save();
        if ($request->target_type == 'products')
            $banner->products()->sync($request->target);
        Toastr::success('Banner added successfully!');
        return redirect()->route('seller.banner.list');
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        $this->authorize('update', $banner);
        return view('seller-views.banner.edit', compact('banner'));
    }

    public function update(UpdateRequest $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $this->authorize('update', $banner);
        $banner->title = $request->title;
        $banner->description = $request->description;
        $banner->banner_type = $request->{$request->resource_type . '_banner_position'};
        $banner->target = $request->target_type;
        $banner->start_at = Carbon::parse($request->start_at)->format('Y-m-d\TH:i');
        $banner->end_at = Carbon::parse($request->end_at)->format('Y-m-d\TH:i');
        if ($request->hasFile('image')) {
            ImageManager::delete("banner/{$banner->photo}");
            $banner->photo = ImageManager::upload('banner/', 'png', $request->file('image'));
        }
        switch ($request->resource_type) {
            case 'category':
                $banner->category_id = $request->category_id;
                break;
                // brand, product, shop
            default:
                $banner->category_id = null;
        }
        $isDirty = $banner->isDirty();
        if ($request->target_type == 'products')
            $isDirty = $isDirty || collect($banner->products()->sync($request->target))->flatten()->isNotEmpty();

        if ($isDirty) {
            $banner->published = 0;
            $banner->save();
        }
        Toastr::success('Banner updated successfully!');
        return redirect()->route('seller.banner.list');
    }

    public function delete(Request $request)
    {
        $banner = Banner::findOrFail($request->id);
        $this->authorize('update', $banner);
        ImageManager::delete("banner/{$banner->photo}");
        return response()->json($banner->delete());
    }
}
