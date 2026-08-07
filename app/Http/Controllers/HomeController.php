<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Consultation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductPriceTier;
use App\Models\Shop;
use App\Models\CellType;
use App\Models\Thumb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public $shop;

    public $cell = [5, 10, 15, 20, 30];

    public function __construct()
    {
        // get shop info
        $shopModel = new Shop;
        $this->shop = $shopModel->getShopInfo();
    }

    public function home()
    {
        // get categories
        $categoryModel = new Category;
        $categories = $categoryModel->getCategoryLists()->keyBy('id');

        // get banner list
        $bannerModel = new Banner;
        $banners = $bannerModel->getBannersDisplay();

        // get Product
        $productModel = new Product;
        $categoryIds = Category::where('show_on_homepage', true)
            ->pluck('id')
            ->toArray();

        if (empty($categoryIds)) {
            $categoryIds = [1, 2, 3, 4, 5];
        }

        $products = $productModel->getProductsByCategory($categoryIds);

        $categoryListProducts = collect($products)->keys()->all();

        return view('UserPage.home',
            [
                'categories' => $categories,
                'shop' => $this->shop,
                'banners' => $banners,
                'categoryListProducts' => $categoryListProducts,
                'products' => $products,
                'keyword' => '',
            ]
        );

    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $price = $request->price;
        $cell = $request->cell;
        $type = $request->type;
        $category_id = $request->category_id;

        $keyword = trim($keyword);
        $keywordAscii = strtolower(Str::ascii($keyword));

        // get categories
        $categoryModel = new Category;
        $categories = $categoryModel->getCategoryLists()->keyBy('id');

        $productModel = new Product;

        $products = $productModel->searchProducts($keywordAscii, $price, $cell, $type, $category_id);
        $categoryListProducts = $products->getCollection()
            ->groupBy('category_id')
            ->toArray();

        $cell_type = CellType::all()->pluck('name', 'id')->toArray();

        return view('UserPage.search', [
            'keyword' => $keyword,
            'products' => $products,
            'categoryListProducts' => $categoryListProducts,
            'shop' => $this->shop,
            'categories' => $categories,
            'cells' => $this->cell,
            'cell_type' => $cell_type,
        ]);

    }

    public function productDetail(Request $request, $id, $slug)
    {
        // get categories
        $categoryModel = new Category;
        $categories = $categoryModel->getCategoryLists()->keyBy('id');

        // get product by id
        $productModel = new Product;
        $product = $productModel->getProductById($id);
        if (empty($product)) {
            abort(404);
        }
        $productPriceTierModel = new ProductPriceTier;
        $productPriceTier = $productPriceTierModel->getByProductId($product['id']);

        // get thumbs
        $thumbModel = new Thumb;
        $thumb_ids = json_decode($product['thumb_id'], true);
        $thumbs = $thumbModel->getThumbByIds($thumb_ids)->pluck('src')->toArray();
        $product['thumbs'] = $thumbs;

        return view('UserPage.product-detail', [
            'product' => $product,
            'shop' => $this->shop,
            'keyword' => '',
            'categories' => $categories,
            'productPriceTier' => $productPriceTier,
        ]);
    }

    public function card()
    {
        // get categories
        $categoryModel = new Category;
        $categories = $categoryModel->getCategoryLists()->keyBy('id');

        return view('UserPage.card', [
            'shop' => $this->shop,
            'keyword' => '',
            'categories' => $categories,
        ]);

    }

    public function order(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',

            'phone' => [
                'required',
                'regex:/^0[3|5|7|8|9][0-9]{8}$/',
            ],

            'address' => 'required',

            'cart' => 'required',
        ], [
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập số điện thoại Việt Nam gồm 10 số.',
        ]);

        try {
            DB::beginTransaction();
            $total = 0;

            foreach ($request->cart as $item) {

                $total += $item['qty'] * $item['price'];
            }

            $order = [
                'customer_name' => $request->customer_name,

                'phone' => $request->phone,

                'address' => $request->address,

                'total_price' => $total,
                'web' => 1,
            ];
            $orderModel = new Order;
            $order = $orderModel->add($order);

            if (! $order) {
                return response()->json([

                    'success' => false,

                ], 400);
            }

            foreach ($request->cart as $item) {
                $orderItem = [
                    'order_id' => $order->id,

                    'product_id' => $item['id'],

                    'qty' => $item['qty'],

                    'price' => $item['price'],
                ];

                $orderItemModel = new OrderItem;
                $orderItem = $orderItemModel->add($orderItem);

                if (! $orderItem) {
                    return response()->json([

                        'success' => false,

                    ], 400);
                }

            }
            DB::commit();

            return response()->json([

                'success' => true,

            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([

                'success' => false,

            ], 400);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|max:255',

            'phone' => [
                'required',
                'regex:/^0[3|5|7|8|9][0-9]{8}$/',
            ],
        ], [
            'customer_name.required' => 'Vui lòng nhập họ tên.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại không hợp lệ. Vui lòng nhập số điện thoại Việt Nam gồm 10 số.',
        ]);

        try {
            Consultation::create([

                'customer_name' => $request->customer_name,

                'phone' => $request->phone,
                'product' => $request->product,
                'web' => 1,

            ]);

            return response()->json([
                'success' => true,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đăng ký thất bại, có lỗi xảy ra!',
            ], 400);
        }

    }
}
