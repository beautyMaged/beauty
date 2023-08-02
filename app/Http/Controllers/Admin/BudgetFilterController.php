<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BudgetFilterRequest;
use App\Model\BudgetFilter;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class BudgetFilterController extends Controller
{
    public function index() {
        $data = BudgetFilter::first();
        return view('admin-views.budget-filter.view', compact('data'));
    }
    public function edit() {
        $data = BudgetFilter::first();
        return view('admin-views.budget-filter.edit', compact('data'));
    }

    public function update(BudgetFilterRequest $request) {
        $data = BudgetFilter::first();

        if ($request->hasFile('image')) {
            $request->image->store('/', 'budget_filter');
            $filename = $request->image->hashName();
            $data->bg = $filename;
        }
        $data->f_num = $request->f_num;
        $data->s_num = $request->s_num;
        $data->t_num = $request->t_num;
        $data->fo_num = $request->fo_num;
        $data->save();

        Toastr::success('تم التحديث بنجاح.');
        return back();

    }
}
