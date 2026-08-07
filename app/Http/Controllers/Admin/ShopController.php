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

        return view('Admin.shop.edit', compact('shopInfo'));
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

        return view('Admin.shop.banners', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        $validated = $request->validate([
            'src' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,gif,webp,jpg|max:5120',
            'display' => 'nullable|boolean',
        ]);

        // if uploaded file, move to public/image and set src to filename
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('image'), $filename);
            $validated['src'] = $filename;
        }

        $validated['display'] = $request->has('display') ? 1 : 0;

        Banner::create($validated);

        return redirect()->route('admin.banners')->with('success', 'Thêm banner thành công');
    }

    public function deleteBanner(Banner $banner)
    {
        // remove file from public/image if exists
        if ($banner->src && file_exists(public_path('image/' . $banner->src))) {
            @unlink(public_path('image/' . $banner->src));
        }

        $banner->delete();

        return redirect()->route('admin.banners')->with('success', 'Xóa banner thành công');
    }

    public function toggleBanner(Banner $banner)
    {
        $banner->display = $banner->display ? 0 : 1;
        $banner->save();

        return redirect()->route('admin.banners')->with('success', 'Cập nhật trạng thái hiển thị banner thành công');
    }
}
