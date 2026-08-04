@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Chỉnh Sửa Thông Tin Shop</h1>

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

            <form action="{{ route('admin.shop.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="shop_name" class="form-label">Tên Shop</label>
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
                           value="{{ old('logo', $shopInfo->logo ?? '') }}" placeholder="https://...">
                    @if($shopInfo->logo ?? false)
                        <small class="text-muted">Logo hiện tại:</small>
                        <div class="mt-2">
                            <img src="{{ $shopInfo->logo }}" alt="Logo" style="max-width: 200px; max-height: 200px;">
                        </div>
                    @endif
                    @error('logo')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Địa Chỉ</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" 
                           id="address" name="address" 
                           value="{{ old('address', $shopInfo->address ?? '') }}" placeholder="Địa chỉ shop">
                    @error('address')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="hotline" class="form-label">Hotline</label>
                    <input type="text" class="form-control @error('hotline') is-invalid @enderror" 
                           id="hotline" name="hotline" 
                           value="{{ old('hotline', $shopInfo->hotline ?? '') }}" placeholder="0123456789">
                    @error('hotline')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="zalo" class="form-label">Zalo</label>
                    <input type="text" class="form-control @error('zalo') is-invalid @enderror" 
                           id="zalo" name="zalo" 
                           value="{{ old('zalo', $shopInfo->zalo ?? '') }}" placeholder="0123456789">
                    @error('zalo')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" 
                           value="{{ old('email', $shopInfo->email ?? '') }}" placeholder="shop@example.com">
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="fanpage" class="form-label">Fanpage Facebook</label>
                    <input type="text" class="form-control @error('fanpage') is-invalid @enderror" 
                           id="fanpage" name="fanpage" 
                           value="{{ old('fanpage', $shopInfo->fanpage ?? '') }}" placeholder="https://facebook.com/...">
                    @error('fanpage')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">Cập Nhật Thông Tin</button>
                </div>
            </form>

            <div class="mt-4">
                <a href="{{ route('admin.banners') }}" class="btn btn-secondary">Quản Lý Banner</a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quản Lý Sản Phẩm</a>
            </div>
        </div>
    </div>
</div>
@endsection
