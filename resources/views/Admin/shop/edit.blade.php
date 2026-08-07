@extends('layouts.app')

@section('content')
<div>
    <div class="mb-4">
        <h1 class="h3 mb-1">Chỉnh Sửa Thông Tin Shop</h1>
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

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.shop.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="shop_name" class="form-label">Tên Shop <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('shop_name') is-invalid @enderror" 
                                   id="shop_name" name="shop_name" 
                                   value="{{ old('shop_name', $shopInfo->shop_name ?? '') }}" required>
                            @error('shop_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo (URL)</label>
                            <input type="text" class="form-control @error('logo') is-invalid @enderror" 
                                   id="logo" name="logo" 
                                   value="{{ old('logo', $shopInfo->logo ?? '') }}" 
                                   placeholder="https://...">
                            @if($shopInfo->logo ?? false)
                                <small class="d-block mt-2">Logo hiện tại:</small>
                                <img src="{{ asset('image/logo/' . $shopInfo->logo) }}" alt="Logo" 
                                     style="max-width: 100%; max-height: 150px; margin-top: 8px; border-radius: 4px;">
                            @endif
                            @error('logo')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Địa Chỉ</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                   id="address" name="address" 
                                   value="{{ old('address', $shopInfo->address ?? '') }}" 
                                   placeholder="Địa chỉ shop">
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="hotline" class="form-label">Hotline</label>
                                <input type="text" class="form-control @error('hotline') is-invalid @enderror" 
                                       id="hotline" name="hotline" 
                                       value="{{ old('hotline', $shopInfo->hotline ?? '') }}" 
                                       placeholder="0123456789">
                                @error('hotline')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label for="zalo" class="form-label">Zalo</label>
                                <input type="text" class="form-control @error('zalo') is-invalid @enderror" 
                                       id="zalo" name="zalo" 
                                       value="{{ old('zalo', $shopInfo->zalo ?? '') }}" 
                                       placeholder="0123456789">
                                @error('zalo')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" 
                                       value="{{ old('email', $shopInfo->email ?? '') }}" 
                                       placeholder="shop@example.com">
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label for="fanpage" class="form-label">Fanpage Facebook</label>
                                <input type="text" class="form-control @error('fanpage') is-invalid @enderror" 
                                       id="fanpage" name="fanpage" 
                                       value="{{ old('fanpage', $shopInfo->fanpage ?? '') }}" 
                                       placeholder="https://facebook.com/...">
                                @error('fanpage')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">Cập Nhật Thông Tin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 mt-3 mt-lg-0">
            <div class="card border-0 shadow-sm sticky-top" style="top: 70px;">
                <div class="card-header bg-light">
                    <h6 class="mb-0 small">Liên Kết Nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.banners') }}" class="btn btn-info btn-sm">
                            🖼️ Quản Lý Banner
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-success btn-sm">
                            📦 Quản Lý Sản Phẩm
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                            🎯 Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
