<?php

namespace App\Services;

use App\Model\Category;
use App\Model\Translation;
use App\CPU\ImageManager;


class CategoryService
{
    public function delete($id)
    {
        Translation::where('translationable_type', Category::class)
            ->where('translationable_id', $id)
            ->delete();
        $category = Category::find($id);
        $category->delete();
        ImageManager::delete('category/' . $category->icon);
    }
    public function deleteSubs($id, $level = 1)
    {
        $subs = Category::where('parent_id', $id)->get();
        if (!empty($subs)) {
            foreach ($subs as $sub)
                $this->delete($sub->id);
            if (--$level)
                $this->deleteSubs($id, $level);
        }
    }
}
