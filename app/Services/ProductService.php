<?php

namespace App\Services;

use App\Model\Category;
use function App\CPU\translate;

class ProductService
{
    public function get_categories(array $products)
    {
        $res = [];
        for ($i = 0; $i < count($products); $i++) {
            $cat = Category::Where('parent_id', $products[$i][0])->get();
            $res[$i] = '<option value="' . 0 . '" disabled selected>---' . translate("Select") . '---</option>';
            foreach ($cat as $row) {
                $name = $row->translations[0]->value ?? $row->name;
                if ($row->id == $products[$i][1])
                    $res[$i] .= '<option value="' . $row->id . '" selected >' . $name . '</option>';
                else
                    $res[$i] .= '<option value="' . $row->id . '">' . $name . '</option>';
            }
        }
        return $res;
    }
}
