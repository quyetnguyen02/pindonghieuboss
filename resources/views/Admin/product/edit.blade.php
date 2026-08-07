@extends('layouts.app')

@section('content')
<div>
    <div class="mb-4">
        <h1 class="h3 mb-1">Chỉnh Sửa Sản Phẩm</h1>
        <p class="text-muted small">{{ $product->name }}</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form id="product-edit-form" action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Tên Sản Phẩm <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name', $product->name) }}"
                           placeholder="Nhập tên sản phẩm" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="category_id" class="form-label">Danh Mục <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror"
                                id="category_id" name="category_id" required>
                            <option value="">-- Chọn Danh Mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6 mb-3">
                        <label for="type" class="form-label">Loại Sản Phẩm <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror"
                                id="type" name="type" required>
                            <option value="">-- Chọn Loại --</option>
                            <option value="0" {{ old('type', $product->type) == '0' ? 'selected' : '' }}>Cell Pin</option>
                            <option value="1" {{ old('type', $product->type) == '1' ? 'selected' : '' }}>Pin Đóng</option>
                            <option value="2" {{ old('type', $product->type) == '2' ? 'selected' : '' }}>Điện</option>
                        </select>
                        @error('type')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Hình ảnh chính -->
                <div class="mb-3">
                    <label for="product_image_main" class="form-label">Hình Ảnh Chính</label>
                    <input type="file" class="form-control @error('product_image_main') is-invalid @enderror"
                           id="product_image_main" name="product_image_main" accept="image/*" onchange="previewMainImage(event)">
                    <small class="form-text text-muted">Định dạng: JPG, PNG, GIF, WebP (tối đa 5MB). Để trống nếu không muốn thay đổi ảnh chính.</small>
                    @error('product_image_main')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                    <div id="main_image_preview" class="mt-3">
                        @if($product->image)
                            <div class="alert alert-info py-2 px-3 small">
                                <strong>Ảnh chính hiện tại:</strong><br>
                                <img src="{{ asset('image/' . $product->image->src) }}" alt="Current Main Image"
                                     style="max-width: 150px; max-height: 150px; border-radius: 4px; margin-top: 10px; object-fit: cover;">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Hình ảnh phụ hiện tại -->
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Hình Ảnh Phụ Hiện Tại</label>
                    @if(isset($subThumbs) && $subThumbs->count() > 0)
                        <p class="text-muted small">Bỏ chọn những ảnh bạn muốn xóa khỏi sản phẩm:</p>
                        <div class="row g-2">
                            @foreach($subThumbs as $thumb)
                                <div class="col-6 col-md-3 col-lg-2">
                                    <div class="card h-100 p-2 text-center border">
                                        <img src="{{ asset('image/' . $thumb->src) }}" alt="Sub Image"
                                             class="img-fluid rounded mb-2" style="height: 100px; object-fit: cover;">
                                        <div class="form-check text-start d-inline-block mx-auto">
                                            <input class="form-check-input" type="checkbox"
                                                   name="existing_thumb_ids[]" value="{{ $thumb->id }}"
                                                   id="thumb_{{ $thumb->id }}" checked>
                                            <label class="form-check-label small" for="thumb_{{ $thumb->id }}">
                                                Giữ lại
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-light border small text-muted">
                            Sản phẩm chưa có ảnh phụ nào.
                        </div>
                    @endif
                </div>

                <!-- Thêm hình ảnh phụ mới -->
                <div class="mb-3">
                    <label for="product_images" class="form-label">Thêm Hình Ảnh Phụ Mới</label>
                    <input type="file" class="form-control @error('product_images.*') is-invalid @enderror"
                           id="product_images" name="product_images[]" accept="image/*" multiple onchange="previewSubImages(event)">
                    <small class="form-text text-muted">Có thể chọn nhiều ảnh cùng lúc (JPG, PNG, GIF, WebP - tối đa 5MB/ảnh)</small>
                    @error('product_images.*')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                    <div id="sub_images_preview" class="row mt-3 g-2"></div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="original_price" class="form-label">Giá Niêm Yết <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('original_price') is-invalid @enderror"
                               id="original_price" name="original_price" value="{{ old('original_price', $product->original_price) }}"
                               placeholder="0" min="0" required>
                        @error('original_price')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label for="sale_price" class="form-label">Giá Khuyến Mãi</label>
                        <input type="number" class="form-control @error('sale_price') is-invalid @enderror"
                               id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}"
                               placeholder="0" min="0">
                        @error('sale_price')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div id="price-tier-section" class="card border-0 bg-light mb-3 d-none">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Giá theo số lượng Cell</h6>
                            <button type="button" class="btn btn-sm btn-primary" onclick="addPriceTierRow()">
                                <i class="fas fa-plus"></i> Thêm mức giá
                            </button>
                        </div>

                        <div id="price-tier-rows">
                            @if(old('price_tiers_qty'))
                                @foreach(old('price_tiers_qty') as $index => $quantity)
                                    <div class="row g-2 align-items-end mb-2 price-tier-row">
                                        <div class="col-md-4">
                                            <label class="form-label">Số lượng tối thiểu</label>
                                            <input type="number" name="price_tiers_qty[]" value="{{ $quantity }}" class="form-control form-control-sm" min="1" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Giá mỗi cell</label>
                                            <input type="number" name="price_tiers_price[]" value="{{ old('price_tiers_price.' . $index) }}" class="form-control form-control-sm" min="0" step="0.01" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-sm btn-danger w-100" onclick="removePriceTierRow(this)">Xóa</button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                @foreach($priceTiers as $tier)
                                    <div class="row g-2 align-items-end mb-2 price-tier-row">
                                        <div class="col-md-4">
                                            <label class="form-label">Số lượng tối thiểu</label>
                                            <input type="number" name="price_tiers_qty[]" value="{{ $tier->from_quantity }}" class="form-control form-control-sm" min="1" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Giá mỗi cell</label>
                                            <input type="number" name="price_tiers_price[]" value="{{ $tier->price }}" class="form-control form-control-sm" min="0" step="0.01" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-sm btn-danger w-100" onclick="removePriceTierRow(this)">Xóa</button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <small class="text-muted">Nhập giá cho từng mức số lượng. Ví dụ: 10cell = 50k, 50cell = 47k, 100cell = 46k, 500cell = 56k.</small>
                    </div>
                </div>

                <!-- Thông Số Sản Phẩm -->
                <div class="card border-0 bg-light mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Thông Số Sản Phẩm</h6>
                            <button type="button" class="btn btn-sm btn-primary" onclick="addSpecificationRow()">
                                <i class="fas fa-plus"></i> Thêm Thông Số
                            </button>
                        </div>

                        <div id="specifications-container">
                            @if(old('spec_keys'))
                                @foreach(old('spec_keys') as $index => $specKey)
                                    <div class="specification-row mb-2">
                                        <div class="row g-2">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control form-control-sm"
                                                       name="spec_keys[]" placeholder="Tên thông số (vd: Dung lượng, Màu sắc...)"
                                                       value="{{ old('spec_keys.' . $index) }}">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control form-control-sm"
                                                       name="spec_values[]" placeholder="Giá trị (vd: 5000mAh, Đen...)"
                                                       value="{{ old('spec_values.' . $index) }}">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeSpecificationRow(this)">
                                                    Xóa
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @elseif($product->specifications && is_array($product->specifications))
                                @foreach($product->specifications as $item)
                                    <div class="specification-row mb-2">
                                        <div class="row g-2">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control form-control-sm"
                                                       name="spec_keys[]" placeholder="Tên thông số (vd: Dung lượng, Màu sắc...)" value="{{ $item['key'] }}">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control form-control-sm"
                                                       name="spec_values[]" placeholder="Giá trị (vd: 5000mAh, Đen...)" value="{{ $item['value'] }}">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeSpecificationRow(this)">
                                                    Xóa
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="specification-row mb-2">
                                    <div class="row g-2">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control form-control-sm"
                                                   name="spec_keys[]" placeholder="Tên thông số (vd: Dung lượng, Màu sắc...)" value="">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control form-control-sm"
                                                   name="spec_values[]" placeholder="Giá trị (vd: 5000mAh, Đen...)" value="">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeSpecificationRow(this)">
                                                Xóa
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($product->discount_percent > 0)
                    <div class="alert alert-info small" role="alert">
                        Chiết khấu hiện tại: <strong>-{{ $product->discount_percent }}%</strong>
                    </div>
                @endif

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-warning">Cập Nhật Sản Phẩm</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-check">
            <input class="form-check-input" type="checkbox" name="visible" form="product-edit-form" {{ $product->visible ? 'checked' : '' }}>
            <span class="form-check-label">Hiển thị sản phẩm (cho khách hàng thấy)</span>
        </label>
    </div>

    <!-- Delete Section -->
    <div class="card border-0 shadow-sm border-danger">
        <div class="card-header bg-danger text-white small">
            <h6 class="mb-0">Xóa Sản Phẩm</h6>
        </div>
        <div class="card-body">
                        <p class="text-muted small mb-3">Ẩn sản phẩm thay vì xóa hoàn toàn. Bạn có thể bật lại hiển thị trong phần chỉnh sửa.</p>
                        <form action="{{ route('admin.products.toggle', $product) }}" method="POST"
                                    onsubmit="return confirm('{{ $product->visible ? 'Bạn chắc chắn muốn ẩn sản phẩm này?' : 'Bạn chắc chắn muốn hiển thị lại sản phẩm này?' }}');">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $product->visible ? 'btn-warning' : 'btn-success' }}">{{ $product->visible ? 'Ẩn Sản Phẩm' : 'Hiển Thị Sản Phẩm' }}</button>
            </form>
        </div>
    </div>
</div>

<script>
function previewMainImage(event) {
    const file = event.target.files[0];
    const imagePreview = document.getElementById('main_image_preview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            imagePreview.innerHTML = `
                <div class="alert alert-info py-2 px-3 small">
                    <strong>Xem trước ảnh chính mới:</strong><br>
                    <img src="${e.target.result}" alt="Preview" style="max-width: 150px; max-height: 150px; border-radius: 4px; margin-top: 10px; object-fit: cover;">
                </div>
            `;
        };
        reader.readAsDataURL(file);
    } else {
        imagePreview.innerHTML = @if($product->image)
            `<div class="alert alert-info py-2 px-3 small">
                <strong>Ảnh chính hiện tại:</strong><br>
                <img src="{{ asset('image/' . $product->image->src) }}" alt="Current Main Image"
                     style="max-width: 150px; max-height: 150px; border-radius: 4px; margin-top: 10px; object-fit: cover;">
            </div>`;
        @else
            '';
        @endif
    }
}

function previewSubImages(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('sub_images_preview');
    previewContainer.innerHTML = '';

    if (files) {
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const col = document.createElement('div');
                col.className = 'col-6 col-md-3 col-lg-2';
                col.innerHTML = `
                    <div class="card p-2 text-center border">
                        <img src="image/ + ${e.target.result}" class="img-fluid rounded" style="height: 100px; object-fit: cover;">
                        <span class="badge bg-success mt-1">Ảnh mới</span>
                    </div>
                `;
                previewContainer.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    }
}

function addSpecificationRow() {
    const container = document.getElementById('specifications-container');
    const newRow = document.createElement('div');
    newRow.className = 'specification-row mb-2';
    newRow.innerHTML = `
        <div class="row g-2">
            <div class="col-md-5">
                <input type="text" class="form-control form-control-sm"
                       name="spec_keys[]" placeholder="Tên thông số (vd: Dung lượng, Màu sắc...)" value="">
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control form-control-sm"
                       name="spec_values[]" placeholder="Giá trị (vd: 5000mAh, Đen...)" value="">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeSpecificationRow(this)">
                    Xóa
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
}

function addPriceTierRow(quantity = '', price = '') {
    const container = document.getElementById('price-tier-rows');
    const row = document.createElement('div');
    row.className = 'row g-2 align-items-end mb-2 price-tier-row';
    row.innerHTML = `
        <div class="col-md-4">
            <label class="form-label">Số lượng tối thiểu</label>
            <input type="number" name="price_tiers_qty[]" value="${quantity}" class="form-control form-control-sm" min="1" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Giá mỗi cell</label>
            <input type="number" name="price_tiers_price[]" value="${price}" class="form-control form-control-sm" min="0" step="0.01" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-sm btn-danger w-100" onclick="removePriceTierRow(this)">Xóa</button>
        </div>
    `;
    container.appendChild(row);
}

function removePriceTierRow(button) {
    button.closest('.price-tier-row').remove();
}

function isCellCategorySelected() {
    const categorySelect = document.getElementById('category_id');

    if (!categorySelect || !categorySelect.selectedOptions.length) {
        return false;
    }

    return categorySelect.selectedOptions[0].textContent.toLowerCase().includes('cell');
}

function togglePriceTierSection() {
    const typeValue = document.getElementById('type').value;
    const section = document.getElementById('price-tier-section');

    if (typeValue === '0' && isCellCategorySelected()) {
        section.classList.remove('d-none');

        const rows = document.querySelectorAll('.price-tier-row');
        if (rows.length === 0) {
            addPriceTierRow();
        }
    } else {
        section.classList.add('d-none');
    }
}

const typeSelect = document.getElementById('type');
const categorySelect = document.getElementById('category_id');
if (typeSelect) {
    typeSelect.addEventListener('change', togglePriceTierSection);
}
if (categorySelect) {
    categorySelect.addEventListener('change', togglePriceTierSection);
}

window.addEventListener('DOMContentLoaded', () => {
    togglePriceTierSection();
});

function removeSpecificationRow(button) {
    button.closest('.specification-row').remove();
}
</script>
@endsection
