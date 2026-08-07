@extends('layouts.app')

@section('content')
<div>
    <div class="mb-4">
        <h1 class="h3 mb-1">Thêm Sản Phẩm Mới</h1>
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

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Tên Sản Phẩm <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                           id="name" name="name" value="{{ old('name') }}"
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
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                            <option value="0" {{ old('type') == '0' ? 'selected' : '' }}>Cell Pin</option>
                            <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Pin Đóng</option>
                        
                        </select>
                        @error('type')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div id="pin-details-section" class="card border-0 bg-light mb-3 d-none">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="cell_type" class="form-label">Thương hiệu Cell</label>
                                <select id="cell_type" name="cell_type" class="form-select @error('cell_type') is-invalid @enderror">
                                    <option value="">-- Chọn Thương hiệu Cell --</option>
                                    @foreach($cellTypes as $cellType)
                                        <option value="{{ $cellType->id }}"
                                                {{ old('cell_type') == $cellType->id ? 'selected' : '' }}>
                                            {{ $cellType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cell_type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="cell_number" class="form-label">Số lượng Cell</label>
                                <select id="cell_number" name="cell_number" class="form-select @error('cell_number') is-invalid @enderror">
                                    <option value="">-- Chọn Số Cell --</option>
                                    @foreach([5, 10, 15, 20, 30] as $count)
                                        <option value="{{ $count }}" {{ old('cell_number') == $count ? 'selected' : '' }}>{{ $count }} Cell</option>
                                    @endforeach
                                </select>
                                @error('cell_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="product_image" class="form-label">Hình Ảnh Chính <span class="text-danger">*</span></label>
                    <input type="file" class="form-control @error('product_image') is-invalid @enderror"
                           id="product_image" name="product_image_main" accept="image/*" required onchange="previewImage(event)">
                    <small class="form-text text-muted">Định dạng: JPG, PNG, GIF, WebP (tối đa 5MB)</small>
                    @error('product_image')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                    <div id="image_preview" class="mt-3"></div>
                </div>

                <div class="mb-3">
                    <label for="product_images" class="form-label">
                        Hình Ảnh Phụ
                    </label>

                    <input
                        type="file"
                        class="form-control @error('product_images.*') is-invalid @enderror"
                        id="product_images"
                        name="product_images[]"
                        accept="image/*"
                        multiple
                        onchange="previewImages(event)"
                    >

                    <small class="form-text text-muted">
                        Có thể chọn nhiều ảnh cùng lúc (JPG, PNG, GIF, WebP - tối đa 5MB/ảnh)
                    </small>

                    @error('product_images.*')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror

                    <div id="image_preview" class="row mt-3 g-2"></div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <label for="original_price" class="form-label">Giá Niêm Yết <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('original_price') is-invalid @enderror"
                               id="original_price" name="original_price" value="{{ old('original_price') }}"
                               placeholder="0" min="0" required>
                        @error('original_price')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <label for="sale_price" class="form-label">Giá Khuyến Mãi</label>
                        <input type="number" class="form-control @error('sale_price') is-invalid @enderror"
                               id="sale_price" name="sale_price" value="{{ old('sale_price') }}"
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

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-success">Thêm Sản Phẩm</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const imagePreview = document.getElementById('image_preview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            imagePreview.innerHTML = `
                <div class="alert alert-info py-2 px-3 small">
                    <strong>Xem trước:</strong>
                    <img src="${e.target.result}" alt="Preview" style="max-width: 150px; max-height: 150px; border-radius: 4px; margin-top: 10px;">
                </div>
            `;
        };
        reader.readAsDataURL(file);
    } else {
        imagePreview.innerHTML = '';
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

function togglePinDetailsSection() {
    const typeValue = document.getElementById('type').value;
    const section = document.getElementById('pin-details-section');

    if (typeValue === '1') {
        section.classList.remove('d-none');
    } else {
        section.classList.add('d-none');
    }
}

const typeSelect = document.getElementById('type');
const categorySelect = document.getElementById('category_id');
if (typeSelect) {
    typeSelect.addEventListener('change', () => {
        togglePriceTierSection();
        togglePinDetailsSection();
    });
}
if (categorySelect) {
    categorySelect.addEventListener('change', togglePriceTierSection);
}

window.addEventListener('DOMContentLoaded', () => {
    togglePriceTierSection();
    togglePinDetailsSection();
});

function removeSpecificationRow(button) {
    button.closest('.specification-row').remove();
}
</script>
@endsection
