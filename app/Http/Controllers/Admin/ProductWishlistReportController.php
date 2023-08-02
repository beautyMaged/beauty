<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use Brian2694\Toastr\Facades\Toastr;

class ProductWishlistReportController extends Controller
{
    /**
     * Product wishlist report list show, search & filtering
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $search = $request['search'];
        $seller_id = $request['seller_id'];
        $sort = $request['sort'] ?? 'ASC';

        $products = self::common_query_filter($request)
                ->where(['request_status'=>1])
                ->paginate(Helpers::pagination_limit())
                ->appends(['search' => $request['search'], 'seller_id' => $seller_id, 'sort' => $request['sort']]);

        return view('admin-views.report.product-in-wishlist', compact('products', 'search', 'seller_id', 'sort'));
    }

    /**
     * Product wishlist report export by excel
     * @param Request $request
     * @return string|\Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\InvalidArgumentException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function export(Request $request)
    {
        $sort = $request['sort'] ?? 'ASC';

        $products = self::common_query_filter($request)->where(['request_status'=>1])->get();

        if ($products->count() == 0) {
            Toastr::warning(\App\CPU\translate('Data is Not available!!!'));
            return back();
        }
        $data = array();
        foreach ($products as $product) {
            $data[] = array(
                'Product Name' => $product->name,
                'Date' => date('d M Y', strtotime($product->created_at)),
                'Total in Wishlist' => $product->wish_list_count,
            );
        }

        return (new FastExcel($data))->download('product_in_wishlist.xlsx');
    }

    public function common_query_filter($request){
        $search = $request['search'];
        $seller_id = $request['seller_id'];
        $sort = $request['sort'] ?? 'ASC';

        return Product::with(['wish_list'])
            ->when($seller_id == 'in_house', function ($query) {
                $query->where(['added_by' => 'admin']);
            })
            ->when($seller_id != 'in_house' && isset($seller_id) && $seller_id != 'all', function ($query) use ($seller_id) {
                $query->where(['added_by' => 'seller', 'user_id' => $seller_id]);
            })
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'Like', '%' . $search . '%');
            })
            ->when($sort, function ($query) use ($sort) {
                $query->withCount('wish_list')
                    ->orderBy('wish_list_count', $sort);
            });
    }
}
