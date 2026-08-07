@extends('layouts.app')

@section('content')
<div>
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Cấu Hình Hiển Thị Danh Mục</h1>
            <p class="text-muted small mb-0">Chọn danh mục nào được hiển thị trên trang chủ.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">← Quay lại Dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row gy-4">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Chọn danh mục hiển thị trên trang chủ</h5>
                    <form action="{{ route('admin.category-display.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3 row gy-2">
                            @foreach($categories as $category)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <label class="form-check form-check-inline w-100">
                                        <input class="form-check-input" type="checkbox"
                                               name="show_categories[]"
                                               value="{{ $category->id }}"
                                               {{ $category->show_on_homepage ? 'checked' : '' }}>
                                        <span class="form-check-label">{{ $category->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Thêm danh mục mới</h5>
                    <form action="{{ route('admin.category-display.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên danh mục</label>
                            <input id="name" name="name" type="text"
                                   value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Nhập tên danh mục">
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">Tạo danh mục</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
