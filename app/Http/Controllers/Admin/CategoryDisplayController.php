<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryDisplayController extends Controller
{
    public function edit()
    {
        $categories = Category::all();

        return view('Admin.category-display.edit', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $validated['name'],
            'show_on_homepage' => false,
        ]);

        return redirect()->route('admin.category-display.edit')
            ->with('success', 'Danh mục mới đã được tạo thành công');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'show_categories' => 'nullable|array',
            'show_categories.*' => 'integer|exists:categories_p,id',
        ]);

        $show = collect($validated['show_categories'] ?? [])->map(fn ($id) => (int) $id)->all();

        Category::query()->update(['show_on_homepage' => false]);
        if (! empty($show)) {
            Category::whereIn('id', $show)->update(['show_on_homepage' => true]);
        }

        return redirect()->route('admin.category-display.edit')
            ->with('success', 'Cập nhật hiển thị category trên trang chủ thành công');
    }
}
