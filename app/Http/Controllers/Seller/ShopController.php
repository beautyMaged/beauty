<?php

namespace App\Http\Controllers\Seller;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Shop;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Seller\RegisterShopRequest;

class ShopController extends Controller
{
    public function view()
    {
        $shop = Shop::where(['seller_id' => auth()->id()])->first();
        if (isset($shop) == false) {
            DB::table('shops')->insert([
                'seller_id' => auth()->id(),
                'name' => auth()->user()->f_name,
                'address' => '',
                'contact' => auth()->user()->phone,
                'image' => 'def.png',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $shop = Shop::where(['seller_id' => auth()->id()])->first();
        }

        return view('seller-views.shop.shopInfo', compact('shop'));
    }

    public function edit($id)
    {
        $shop = Shop::where(['seller_id' =>  auth()->id()])->first();
        return view('seller-views.shop.edit', compact('shop'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'banner'      => 'mimes:png,jpg,jpeg|max:2048',
            'image'       => 'mimes:png,jpg,jpeg|max:2048',
        ], [
            'banner.mimes'   => 'Banner image type jpg, jpeg or png',
            'banner.max'     => 'Banner Maximum size 2MB',
            'image.mimes'    => 'Image type jpg, jpeg or png',
            'image.max'      => 'Image Maximum size 2MB',
        ]);

        $shop = Shop::find($id);
        $shop->name = $request->name;
        $shop->address = $request->address;
        $shop->contact = $request->contact;
        if ($request->image) {
            $shop->image = ImageManager::update('shop/', $shop->image, 'png', $request->file('image'));
        }
        if ($request->banner) {
            $shop->banner = ImageManager::update('shop/banner/', $shop->banner, 'png', $request->file('banner'));
        }
        $shop->save();

        Toastr::info('Shop updated successfully!');
        return redirect()->route('seller.shop.view');
    }

    public function vacation_add(Request $request, $id){
        $shop = Shop::find($id);
        $shop->vacation_status = $request->vacation_status == 'on' ? 1 : 0;
        $shop->vacation_start_date = $request->vacation_start_date;
        $shop->vacation_end_date = $request->vacation_end_date;
        $shop->vacation_note = $request->vacation_note;
        $shop->save();

        Toastr::success('Vacation mode updated successfully!');
        return redirect()->back();
    }

    public function temporary_close(Request $request){
        $shop = Shop::find($request->id);

        $shop->temporary_close = $request->status == 'checked' ? 1 : 0;
        $shop->save();

        return response()->json(['status' => true], 200);
    }

    public function store(RegisterShopRequest $request ){
    	try{
    		$shopData = $this->shopData($request);

            $newShop = Shop::create($shopData);
		  
            $branches = $request->branches;

            // create branches
            foreach($branches as $branch){
                $newShop->branches()->create($branch);
            }
            
            $connections = $request->connections;
            // create connections
            foreach($connections as $connection){
                $newShop->connections()->create($connection);
            }
            // agency if present
            $agency = $request->agency ?? null;

            if($agency){
            
                $newShop->agency()->create($agency);
            
            }
            // anufacturer if present
            $manufacturer = $request->manufacturer ?? null;

            if($manufacturer){
            
                $newShop->agency()->create($manufacturer);
            
            }
            // policies of the seller
            $policies = $request->policies;

            foreach($policies as $policy){
                Auth::seller()->policies()->where('id',$policy->id)->create($connection);
            }
            
            $refund_policy = $request->refund_policy;
            
            $fast_deliveries = $request->fast_deliveries;
            
            $new_policies = $request->new_policies ?? null;
            
            $shop_repository = $request->shop_repository;
            
            $badges = $request->badges ?? null;
            
            $delivery_companies = $request->delivery_companies ?? null;
            
            $fast_deliveries = $request->fast_deliveries ?? null;
            
            $one_day_deliveries = $request->one_day_deliveries ?? null;
		
    	}catch(Exception $e){
    		Log::info(response()->json($e->getMessage()));
    	}
        
    }

    private function shopData($request){
        
        return  $request->only([
            'trade_name',
            'e_trade_name',
            'type',
            'platform',
            'image',
            'banner',
            'commercial_record',
            'trade_gov_no',
            'auth_authority',
            'AUTH_no',
            'tax_no',
            'city_id',
            'country_id']) ?? null;

    }

}
