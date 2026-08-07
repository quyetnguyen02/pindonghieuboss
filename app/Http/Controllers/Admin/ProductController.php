<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Thumb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('image')->latest()->paginate(20);

        return view('Admin.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $thumbs = Thumb::all();

        return view('Admin.product.create', compact('categories', 'thumbs'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories_p,id',
            'product_image_main' => 'required|image|mimes:jpeg,png,gif,webp,jpg|max:5120',
            'product_images' => 'nullable|array',
            'product_images.*' => 'image|mimes:jpeg,png,gif,webp,jpg|max:5120',
            'original_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'type' => 'required|in:0,1,2',
            'spec_keys' => 'nullable|array',
            'spec_keys.*' => 'nullable|string',
            'spec_values' => 'nullable|array',
            'spec_values.*' => 'nullable|string',
        ]);

        // Xử lý ảnh sản phẩm chính -> lưu public/image và tạo bản ghi ở thumbs_p
        $imageId = null;
        if ($request->hasFile('product_image_main')) {
            $file = $request->file('product_image_main');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('image'), $filename);

            $mainThumb = Thumb::create([
                'src' => $filename,
            ]);
            $imageId = $mainThumb->id;
        }

        // Xử lý các ảnh sản phẩm phụ -> lưu public/image và tạo bản ghi ở thumbs_p
        $thumbIds = [];
        $thumbIds[] = $imageId;
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $file) {
                if ($file->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('image'), $filename);

                    $subThumb = Thumb::create([
                        'src' => $filename,
                    ]);
                    $thumbIds[] = $subThumb->id;
                }
            }
        }

        // Xử lý thông số kỹ thuật (specifications) dạng [{"key": "...", "value": "..."}, ...]
        $specifications = [];
        if (! empty($validated['spec_keys']) && ! empty($validated['spec_values'])) {
            foreach ($validated['spec_keys'] as $key => $specKey) {
                if (! empty($specKey) && isset($validated['spec_values'][$key]) && $validated['spec_values'][$key] !== '') {
                    $specifications[] = [
                        'key' => trim($specKey),
                        'value' => trim($validated['spec_values'][$key]),
                    ];
                }
            }
        }

        $data = [
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'image_id' => $imageId,
            'thumb_id' => ! empty($thumbIds) ? json_encode($thumbIds) : null,
            'original_price' => $validated['original_price'],
            'sale_price' => $validated['sale_price'],
            'type' => $validated['type'],
            'specifications' => ! empty($specifications) ? json_encode($specifications, JSON_UNESCAPED_UNICODE) : null,
            'visible' => 1,
        ];

        Product::create($data);
        DB::commit();

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm sản phẩm: ' . $e->getMessage());
        }

    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load('image');

        $subThumbIds = [];
        if (! empty($product->thumb_id)) {
            $subThumbIds = is_array($product->thumb_id)
                ? $product->thumb_id
                : (json_decode($product->thumb_id, true) ?? []);
        }

        // Loại bỏ $product->image_id khỏi $subThumbIds nếu $subThumbIds chứa cả main image_id
        $subThumbIds = array_diff($subThumbIds, [$product->image_id]);

        $subThumbs = ! empty($subThumbIds) ? Thumb::whereIn('id', $subThumbIds)->get() : collect();


        return view('Admin.product.edit', compact('product', 'categories', 'subThumbs'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories_p,id',
                'product_image_main' => 'nullable|image|mimes:jpeg,png,gif,webp,jpg|max:5120',
                'product_images' => 'nullable|array',
                'product_images.*' => 'image|mimes:jpeg,png,gif,webp,jpg|max:5120',
                'existing_thumb_ids' => 'nullable|array',
                'existing_thumb_ids.*' => 'integer|exists:thumbs_p,id',
                'original_price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'type' => 'required|in:0,1,2',
                'spec_keys' => 'nullable|array',
                'spec_keys.*' => 'nullable|string',
                'spec_values' => 'nullable|array',
                'spec_values.*' => 'nullable|string',
            ]);

            // 1. Xử lý ảnh sản phẩm chính
            $imageId = $product->image_id;
            if ($request->hasFile('product_image_main')) {
                $file = $request->file('product_image_main');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('image'), $filename);

                $mainThumb = Thumb::create([
                    'src' => $filename,
                ]);
                $imageId = $mainThumb->id;
            }

            // 2. Xử lý ảnh phụ cũ được chọn giữ lại
            $subThumbIds = $request->input('existing_thumb_ids', []);

            // Thêm các ảnh phụ mới tải lên
            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $file) {
                    if ($file->isValid()) {
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('image'), $filename);

                        $subThumb = Thumb::create([
                            'src' => $filename,
                        ]);
                        $subThumbIds[] = $subThumb->id;
                    }
                }
            }

            // Loại bỏ ID ảnh chính ra khỏi danh sách ảnh phụ (tránh trùng lặp)
            $subThumbIds = array_diff($subThumbIds, [$imageId]);

            // Đảm bảo ID ảnh chính ($imageId) luôn nằm ở vị trí ĐẦU TIÊN trong mảng ảnh
            $allThumbIds = array_values(array_merge([$imageId], $subThumbIds));

            // 3. Xử lý thông số kỹ thuật (specifications) dạng [{"key": "...", "value": "..."}, ...]
            $specifications = [];
            if (! empty($validated['spec_keys']) && ! empty($validated['spec_values'])) {
                foreach ($validated['spec_keys'] as $key => $specKey) {
                    if (! empty($specKey) && isset($validated['spec_values'][$key]) && $validated['spec_values'][$key] !== '') {
                        $specifications[] = [
                            'key' => trim($specKey),
                            'value' => trim($validated['spec_values'][$key]),
                        ];
                    }
                }
            }
        

            $product->update([
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'image_id' => $imageId,
                'thumb_id' => ! empty($allThumbIds) ? json_encode($allThumbIds) : null,
                'original_price' => $validated['original_price'],
                'sale_price' => $validated['sale_price'],
                'type' => $validated['type'],
                'specifications' => ! empty($specifications) ? $specifications : null,
                'visible' => $request->has('visible') ? 1 : 0,
            ]);

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật sản phẩm: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        $product->visible = 0;
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Đã ẩn sản phẩm thành công');
    }

    public function toggleVisibility(Product $product)
    {
        $product->visible = $product->visible ? 0 : 1;
        $product->save();

        $message = $product->visible ? 'Đã hiển thị sản phẩm' : 'Đã ẩn sản phẩm';

        return redirect()->route('admin.products.index')->with('success', $message);
    }
}
