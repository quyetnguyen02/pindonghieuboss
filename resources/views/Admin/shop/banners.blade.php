@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <h1>Quản Lý Banner</h1>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Form Thêm Banner -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Thêm Banner Mới</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.banners.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="src" class="form-label">URL Banner</label>
                            <input type="text" class="form-control @error('src') is-invalid @enderror" 
                                   id="src" name="src" 
                                   value="{{ old('src') }}" 
                                   placeholder="https://example.com/banner.jpg" required>
                            @error('src')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Nhập URL hình ảnh banner</small>
                        </div>

                        <button type="submit" class="btn btn-success">Thêm Banner</button>
                    </form>
                </div>
            </div>

            <!-- Danh Sách Banner -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Danh Sách Banner ({{ $banners->count() }})</h5>
                </div>
                <div class="card-body">
                    @if($banners->isEmpty())
                        <p class="text-muted">Chưa có banner nào. Hãy thêm banner mới!</p>
                    @else
                        <div class="row">
                            @foreach($banners as $banner)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100">
                                        <img src="{{ $banner->src }}" class="card-img-top" alt="Banner" style="height: 200px; object-fit: cover;">
                                        <div class="card-body">
                                            <p class="card-text text-muted small">ID: {{ $banner->id }}</p>
                                            <p class="card-text text-truncate">{{ $banner->src }}</p>
                                        </div>
                                        <div class="card-footer">
                                            <form action="{{ route('admin.banners.delete', $banner) }}" method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Xác nhận xóa banner này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.shop.edit') }}" class="btn btn-secondary">← Quay Lại Thông Tin Shop</a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quản Lý Sản Phẩm</a>
            </div>
        </div>
    </div>
</div>
@endsection
