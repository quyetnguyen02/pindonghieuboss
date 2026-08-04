@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Thêm Sản Phẩm Mới</h1>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Tên Sản Phẩm <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" 
                           value="{{ old('name') }}" 
                           placeholder="Nhập tên sản phẩm" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
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

                <div class="mb-3">
                    <label for="image_id" class="form-label">Hình Ảnh Chính <span class="text-danger">*</span></label>
                    <select class="form-select @error('image_id') is-invalid @enderror" 
                            id="image_id" name="image_id" required 
                            onchange="previewImage()">
                        <option value="">-- Chọn Hình Ảnh --</option>
                        @foreach($thumbs as $thumb)
                            <option value="{{ $thumb->id }}" {{ old('image_id') == $thumb->id ? 'selected' : '' }}>
                                {{ $thumb->src }}
                            </option>
                        @endforeach
                    </select>
                    @error('image_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <div id="image_preview" class="mt-3"></div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="original_price" class="form-label">Giá Niêm Yết <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('original_price') is-invalid @enderror" 
                                   id="original_price" name="original_price" 
                                   value="{{ old('original_price') }}" 
                                   placeholder="0" min="0" required>
                            @error('original_price')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="sale_price" class="form-label">Giá Khuyến Mãi</label>
                            <input type="number" class="form-control @error('sale_price') is-invalid @enderror" 
                                   id="sale_price" name="sale_price" 
                                   value="{{ old('sale_price') }}" 
                                   placeholder="0" min="0">
                            @error('sale_price')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Loại Sản Phẩm <span class="text-danger">*</span></label>
                    <select class="form-select @error('type') is-invalid @enderror" 
                            id="type" name="type" required>
                        <option value="">-- Chọn Loại --</option>
                        <option value="0" {{ old('type') == '0' ? 'selected' : '' }}>Phụ Kiện</option>
                        <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Pin</option>
                        <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Điện</option>
                    </select>
                    @error('type')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">Thêm Sản Phẩm</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage() {
    const imageId = document.getElementById('image_id').value;
    const imagePreview = document.getElementById('image_preview');
    
    if (imageId) {
        const selectedOption = document.querySelector(`#image_id option[value="${imageId}"]`);
        const imageSrc = selectedOption.text;
        imagePreview.innerHTML = `
            <div>
                <small class="text-muted">Xem trước:</small>
                <img src="${imageSrc}" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 4px;">
            </div>
        `;
    } else {
        imagePreview.innerHTML = '';
    }
}
</script>
@endsection
