<?php

namespace App\Http\Controllers\Admin;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\StaticPageRequest;
use App\Model\StaticPage;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    public function index() {
        $pages = StaticPage::paginate(10);
        return view('admin-views.static-pages.view', compact('pages'));

    }
    public function store(StaticPageRequest $request) {
//        return $request;
        $page = new StaticPage;
        $page->title = $request->title[array_search('en', $request->lang)];
        $page->description = $request->description[array_search('en', $request->lang)];
        $page->image = ImageManager::upload('static-pages/', 'png', $request->file('image'));
        $page->save();


        $data = [];
        foreach ($request->lang as $index => $key) {
            if ($request->title[$index] && $key != 'en') {
                array_push($data, array(
                    'translationable_type' => 'App\Model\StaticPage',
                    'translationable_id' => $page->id,
                    'locale' => $key,
                    'key' => 'title',
                    'value' => $request->title[$index],
                ));
            }
            if ($request->description[$index] && $key != 'en') {
                array_push($data, array(
                    'translationable_type' => 'App\Model\StaticPage',
                    'translationable_id' => $page->id,
                    'locale' => $key,
                    'key' => 'description',
                    'value' => $request->description[$index],
                ));
            }
        }
        if (count($data)) {
            Translation::insert($data);
        }


        Toastr::success('Page added successfully!');
        return back();
    }
    public function edit($id) {
        $page = StaticPage::find($id);
        if ($page) {
            return view('admin-views.static-pages.edit', compact('page'));
        } else {
            Toastr::error('حدث خطأ ما .. برجاء المحاولة فيما بعد');
            return redirect()->back();
        }
    }
    public function update(StaticPageRequest $request, $id) {
//        return $request;

        $page = StaticPage::find($id);

        if ($request->image) {
            $page->image = ImageManager::update('static-pages/', $page->image, 'png', $request->file('image'));
        }
        $page->title = $request->title[array_search('en', $request->lang)];
        $page->description = $request->description[array_search('en', $request->lang)];

        foreach ($request->lang as $index => $key) {
            if ($request->title[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\StaticPage',
                        'translationable_id' => $page->id,
                        'locale' => $key,
                        'key' => 'title'],
                    ['value' => $request->title[$index]]
                );
            }
            if ($request->description[$index] && $key != 'en') {
                Translation::updateOrInsert(
                    ['translationable_type' => 'App\Model\StaticPage',
                        'translationable_id' => $page->id,
                        'locale' => $key,
                        'key' => 'description'],
                    ['value' => $request->description[$index]]
                );
            }

        }
        $page->save();
        Toastr::success(' updated successfully.');
        return back();
    }
    public function delete(Request $request) {
        $br = StaticPage::find($request->id);
        ImageManager::delete('/banner/' . $br['photo']);
        StaticPage::where('id', $request->id)->delete();
        return response()->json();
    }
}
