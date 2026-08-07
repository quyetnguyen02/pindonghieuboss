@extends('layouts.app')

@section('content')
<div>
    <div class="mb-4">
        <h1 class="h3 mb-1">Quản Lý Banner</h1>
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Add Banner Form -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 small">Thêm Banner Mới</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="image" class="form-label">Tải Lên Ảnh Banner</label>
                    <input type="file" accept="image/*" class="form-control @error('image') is-invalid @enderror"
                           id="image" name="image">
                    @error('image')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Chấp nhận các định dạng ảnh (jpg, png, webp,...)</small>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="display" name="display" value="1"
                           @if(old('display', true)) checked @endif>
                    <label class="form-check-label small" for="display">Hiển thị ngay</label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-sm">Thêm Banner</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Banner List -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0 small">Danh Sách Banner ({{ $banners->count() }})</h5>
        </div>
        <div class="card-body">
            @if($banners->isEmpty())
                <p class="text-muted text-center py-4">Chưa có banner nào. Hãy thêm banner mới!</p>
            @else
                <div class="row g-3">
                    @foreach($banners as $banner)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="card h-100 border shadow-sm">
                                <img src="{{ asset('image/' . $banner->src) }}" class="card-img-top" alt="Banner"
                                     style="height: 150px; object-fit: cover;">
                                <div class="card-body">
                                    <p class="card-text text-muted small">ID: {{ $banner->id }}</p>
                                    <p class="card-text text-truncate small" title="{{ $banner->src }}">
                                        {{ Str::limit($banner->src, 30) }}
                                    </p>
                                </div>
                                <div class="card-footer d-flex gap-2">
                                    <form action="{{ route('admin.banners.toggle', $banner) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm w-100 {{ $banner->display ? 'btn-outline-primary' : 'btn-outline-secondary' }}">
                                            {{ $banner->display ? 'Tắt Hiển Thị' : 'Bật Hiển Thị' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.banners.delete', $banner) }}" method="POST" onsubmit="return confirm('Xác nhận xóa banner này?');" class="flex-grow-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100">Xóa</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="mt-4 d-flex flex-wrap gap-2">
        <a href="{{ route('admin.shop.edit') }}" class="btn btn-secondary btn-sm">← Thông Tin Shop</a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">Quản Lý Sản Phẩm</a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">Dashboard</a>
    </div>
</div>
@endsection
