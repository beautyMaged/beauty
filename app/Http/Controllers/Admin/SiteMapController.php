<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Sitemap\SitemapGenerator;
use Illuminate\Support\Facades\Route;
use Brian2694\Toastr\Facades\Toastr;
use function App\CPU\translate;
use Illuminate\Support\Facades\Storage;

class SiteMapController extends Controller
{
    public function index()
    {
        return view('admin-views.site-map.view');
    }

    public function download(){
        SitemapGenerator::create(url('/'))->writeToFile('public/sitemap.xml');
        return response()->download(public_path('sitemap.xml'));
    }
}
