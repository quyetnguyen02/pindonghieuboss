<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_banners' => Banner::count(),
            'total_orders' => Order::count(),
        ];

        $recentProducts = Product::with('image')->latest()->take(5)->get();
        $shopInfo = Shop::first();

        return view('admin.dashboard', compact('stats', 'recentProducts', 'shopInfo'));
    }
}
