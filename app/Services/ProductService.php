<?php

namespace App\Services;

use App\Model\Category;
use function App\CPU\translate;

class ProductService
{
    public function get_categories(array $product)
    {
        $res = [];
        for ($i = 0; $i < count($product); $i++) {
            $cat = Category::Where('parent_id', $product[$i][0])->get();
            $res[$i] = '<option value="' . 0 . '" disabled selected>---' . translate("Select") . '---</option>';
            foreach ($cat as $row) {
                if ($row->id == $product[$i][1]) {
                    $res[$i] .= '<option value="' . $row->id . '" selected >' . $row->name . '</option>';
                } else {
                    $res[$i] .= '<option value="' . $row->id . '">' . $row->name . '</option>';
                }
            }
        }
        return $res;
    }

    public function update_categories($category, $sub_category, $sub_sub_category)
    {
        $ids = [];
        if ($category != null) {
            array_push($ids, [
                'id' => $category,
                'position' => 1,
            ]);
        }
        if ($sub_category != null) {
            array_push($ids, [
                'id' => $sub_category,
                'position' => 2,
            ]);
        }
        if ($sub_sub_category != null) {
            array_push($ids, [
                'id' => $sub_sub_category,
                'position' => 3,
            ]);
        }
        return json_encode($ids);
    }
}
