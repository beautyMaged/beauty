<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Model\Partner;
use App\Http\Controllers\Controller;

class PartnerController extends Controller
{
    public function store(Request $request)
    {
        $partner = new Partner;
        $partner->data = json_encode($request->all());
        $partner->save();
    }
}
