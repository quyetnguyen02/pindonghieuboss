<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function editShopInfo()
    {
        $shopInfo = Shop::first() ?? new Shop;

        return view('admin.shop.edit', compact('shopInfo'));
    }

    public function updateShopInfo(Request $request)
    {
        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'logo' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'hotline' => 'nullable|string|max:20',
            'zalo' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'fanpage' => 'nullable|string',
        ]);

        $shopInfo = Shop::first() ?? new Shop;
        $shopInfo->fill($validated);
        $shopInfo->save();

        return redirect()->route('admin.shop.edit')->with('success', 'Cập nhật thông tin shop thành công');
    }

    public function manageBanners()
    {
        $banners = Banner::latest()->get();

        return view('admin.shop.banners', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        $validated = $request->validate([
            'src' => 'required|string',
        ]);

        Banner::create($validated);

        return redirect()->route('admin.banners')->with('success', 'Thêm banner thành công');
    }

    public function deleteBanner(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('admin.banners')->with('success', 'Xóa banner thành công');
    }
}
