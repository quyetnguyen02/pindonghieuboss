<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CellType;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPriceTier;
use App\Models\Thumb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('image')->orderByDesc('id');

        if ($search = $request->input('search')) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('id', $search);
            });
        }

        if ($minPrice = $request->input('price_min')) {
            $query->whereRaw(
                'IF(sale_price IS NULL OR sale_price = 0, original_price, sale_price) >= ?',
                [$minPrice]
            );
        }

        if ($maxPrice = $request->input('price_max')) {
            $query->whereRaw(
                'IF(sale_price IS NULL OR sale_price = 0, original_price, sale_price) <= ?',
                [$maxPrice]
            );
        }

        $products = $query->paginate(20)->withQueryString();

        return view('Admin.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $thumbs = Thumb::all();
        $cellTypes = CellType::orderBy('name')->get();

        return view('Admin.product.create', compact('categories', 'thumbs', 'cellTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'nullable|string|max:255',
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
                'price_tiers_qty' => 'nullable|array',
                'price_tiers_qty.*' => 'nullable|integer|min:1',
                'price_tiers_price' => 'nullable|array',
                'price_tiers_price.*' => 'nullable|numeric|min:0',
                'cell_type' => 'required_if:type,1|nullable|exists:cell_types,id',
                'cell_number' => 'required_if:type,1|nullable|integer|in:5,10,15,20,30',
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
            'sku' => $validated['sku'] ?? null,
            'category_id' => $validated['category_id'],
            'image_id' => $imageId,
            'thumb_id' => ! empty($thumbIds) ? json_encode($thumbIds) : null,
            'original_price' => $validated['original_price'] ?? '0',
            'sale_price' => $validated['sale_price'] ?? '0',
            'type' => $validated['type'],
            'cell_type' => $validated['type'] === '1' ? $validated['cell_type'] : null,
            'cell_number' => $validated['type'] === '1' ? $validated['cell_number'] : null,
            'specifications' => ! empty($specifications) ? json_encode($specifications, JSON_UNESCAPED_UNICODE) : null,
            'visible' => 1,
        ];

        $product = Product::create($data);

        if ($product->type === '0' && ! empty($validated['price_tiers_qty']) && ! empty($validated['price_tiers_price'])) {
            ProductPriceTier::where('product_id', $product->id)->delete();

            foreach ($validated['price_tiers_qty'] as $index => $quantity) {
                $price = $validated['price_tiers_price'][$index] ?? null;

                if ($quantity && $price !== null) {
                    ProductPriceTier::create([
                        'product_id' => $product->id,
                        'from_quantity' => $quantity,
                        'price' => $price,
                    ]);
                }
            }
        }
        // dd($product);
        DB::commit();

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công');
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm sản phẩm: ' . $e->getMessage());
        }

    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $cellTypes = CellType::orderBy('name')->get();
        $product->load('image');
        $priceTiers = ProductPriceTier::where('product_id', $product->id)
            ->orderBy('from_quantity')
            ->get();

        $subThumbIds = [];
        if (! empty($product->thumb_id)) {
            $subThumbIds = is_array($product->thumb_id)
                ? $product->thumb_id
                : (json_decode($product->thumb_id, true) ?? []);
        }

        // Loại bỏ $product->image_id khỏi $subThumbIds nếu $subThumbIds chứa cả main image_id
        $subThumbIds = array_diff($subThumbIds, [$product->image_id]);

        $subThumbs = ! empty($subThumbIds) ? Thumb::whereIn('id', $subThumbIds)->get() : collect();


        return view('Admin.product.edit', compact('product', 'categories', 'cellTypes', 'subThumbs', 'priceTiers'));
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
                'cell_type' => 'required_if:type,1|nullable|exists:cell_types,id',
                'cell_number' => 'required_if:type,1|nullable|integer|in:5,10,15,20,30',
                'spec_keys' => 'nullable|array',
                'spec_keys.*' => 'nullable|string',
                'spec_values' => 'nullable|array',
                'spec_values.*' => 'nullable|string',
                'price_tiers_qty' => 'nullable|array',
                'price_tiers_qty.*' => 'nullable|integer|min:1',
                'price_tiers_price' => 'nullable|array',
                'price_tiers_price.*' => 'nullable|numeric|min:0',
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
                'cell_type' => $validated['type'] === '1' ? $validated['cell_type'] : null,
                'cell_number' => $validated['type'] === '1' ? $validated['cell_number'] : null,
                'specifications' => ! empty($specifications) ? json_encode($specifications, JSON_UNESCAPED_UNICODE) : null,
                'visible' => $request->has('visible') ? 1 : 0,
            ]);

            if ($product->type === '0') {
                ProductPriceTier::where('product_id', $product->id)->delete();

                if (! empty($validated['price_tiers_qty']) && ! empty($validated['price_tiers_price'])) {
                    foreach ($validated['price_tiers_qty'] as $index => $quantity) {
                        $price = $validated['price_tiers_price'][$index] ?? null;

                        if ($quantity && $price !== null) {
                            ProductPriceTier::create([
                                'product_id' => $product->id,
                                'from_quantity' => $quantity,
                                'price' => $price,
                            ]);
                        }
                    }
                }
            } else {
                ProductPriceTier::where('product_id', $product->id)->delete();
            }

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
