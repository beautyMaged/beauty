<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CPU\FileManagerLogic;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Storage;
use Madnest\Madzipper\Facades\Madzipper;

class FileManagerController extends Controller
{

    public function index($folder_path = "cHVibGlj")
    {
        $file = Storage::files('public/'.base64_decode($folder_path));

        $directories = Storage::directories(base64_decode($folder_path));

        $folders = FileManagerLogic::format_file_and_folders($directories, 'folder');
        $files = FileManagerLogic::format_file_and_folders($file, 'file');
        $data = array_merge($folders, $files);

        return view('admin-views.file-manager.index', compact('data', 'folder_path'));
    }


    public function upload(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info('This option is disabled for demo.');
            return back();
        }

        $request->validate([
            'images' => 'required_without:file',
            'file' => 'required_without:images',
            'path' => 'required',
        ]);
        if ($request->hasfile('images')) {
            $images = $request->file('images');

            foreach ($images as $image) {
                $name = $image->getClientOriginalName();
                Storage::disk('local')->put($request->path . '/' . $name, file_get_contents($image));
            }
        }
        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $name = $file->getClientOriginalName();

            Madzipper::make($file)->extractTo('storage/app/' . $request->path);
            // Storage::disk('local')->put($request->path.'/'. $name, file_get_contents($file));

        }
        Toastr::success(\App\CPU\translate('image_uploaded_successfully'));
        return back()->with('success', \App\CPU\translate('image_uploaded_successfully'));
    }


    public function download($file_name)
    {
        return Storage::download(base64_decode($file_name));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($file_path)
    {
        Storage::disk('local')->delete(base64_decode($file_path));
        Toastr::success(trans('messages.image_deleted_successfully'));
        return back()->with('success', trans('messages.image_deleted_successfully'));
    }
}
