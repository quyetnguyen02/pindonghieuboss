@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Title -->
    <div class="mb-4">
        <h1 class="h2">Dashboard - Quản Trị Shop</h1>
        <p class="text-muted">Chào mừng đến với bảng điều khiển quản lý</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2">Tổng Sản Phẩm</h6>
                            <h2 class="mb-0">{{ $stats['total_products'] }}</h2>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.3;">
                            📦
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <a href="{{ route('admin.products.index') }}" class="text-decoration-none small">
                        Xem chi tiết →
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2">Tổng Banner</h6>
                            <h2 class="mb-0">{{ $stats['total_banners'] }}</h2>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.3;">
                            🖼️
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <a href="{{ route('admin.banners') }}" class="text-decoration-none small">
                        Xem chi tiết →
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2">Tổng Đơn Hàng</h6>
                            <h2 class="mb-0">{{ $stats['total_orders'] }}</h2>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.3;">
                            🛒
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <a href="#" class="text-decoration-none small disabled">
                        Xem chi tiết →
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2">Thông Tin Shop</h6>
                            <p class="mb-0 small">
                                <strong>{{ $shopInfo->shop_name ?? 'Chưa cập nhật' }}</strong>
                            </p>
                        </div>
                        <div style="font-size: 2.5rem; opacity: 0.3;">
                            🏪
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <a href="{{ route('admin.shop.edit') }}" class="text-decoration-none small">
                        Chỉnh sửa →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sản Phẩm Mới Nhất -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">5 Sản Phẩm Mới Nhất</h5>
                </div>
                <div class="card-body p-0">
                    @if($recentProducts->isEmpty())
                        <div class="p-4 text-center text-muted">
                            <p>Chưa có sản phẩm nào</p>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary">
                                Thêm sản phẩm
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tên Sản Phẩm</th>
                                        <th>Giá</th>
                                        <th>Ngày Tạo</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentProducts as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($product->image)
                                                        <img src="{{ $product->image->src }}" 
                                                             alt="{{ $product->name }}"
                                                             style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                                    @endif
                                                    <span>{{ Str::limit($product->name, 30) }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ number_format($product->sale_price ?? $product->original_price, 0, '.', ',') }} đ
                                                </span>
                                            </td>
                                            <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.products.edit', $product) }}" 
                                                   class="btn btn-sm btn-warning">Sửa</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-light text-end">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">
                        Xem tất cả sản phẩm
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Links & Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">Hành Động Nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            ➕ Thêm Sản Phẩm
                        </a>
                        <a href="{{ route('admin.banners') }}" class="btn btn-info">
                            🖼️ Quản Lý Banner
                        </a>
                        <a href="{{ route('admin.shop.edit') }}" class="btn btn-secondary">
                            📝 Chỉnh Thông Tin Shop
                        </a>
                        <a href="{{ route('admin.consultations') }}" class="btn btn-warning">
                            💬 Xem Tư Vấn
                        </a>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">Thông Tin Shop</h5>
                </div>
                <div class="card-body">
                    @if($shopInfo)
                        <div class="mb-3">
                            <small class="text-muted">Tên Shop</small>
                            <p class="mb-1"><strong>{{ $shopInfo->shop_name }}</strong></p>
                        </div>
                        @if($shopInfo->address)
                            <div class="mb-3">
                                <small class="text-muted">Địa Chỉ</small>
                                <p class="mb-1">{{ $shopInfo->address }}</p>
                            </div>
                        @endif
                        @if($shopInfo->hotline)
                            <div class="mb-3">
                                <small class="text-muted">Hotline</small>
                                <p class="mb-1">
                                    <a href="tel:{{ $shopInfo->hotline }}">{{ $shopInfo->hotline }}</a>
                                </p>
                            </div>
                        @endif
                        @if($shopInfo->email)
                            <div class="mb-3">
                                <small class="text-muted">Email</small>
                                <p class="mb-1">
                                    <a href="mailto:{{ $shopInfo->email }}">{{ $shopInfo->email }}</a>
                                </p>
                            </div>
                        @endif
                    @else
                        <p class="text-muted">Chưa cập nhật thông tin shop</p>
                    @endif
                    <hr>
                    <a href="{{ route('admin.shop.edit') }}" class="btn btn-sm btn-outline-primary w-100">
                        Chỉnh Sửa Thông Tin
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Overview -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">📚 Hướng Dẫn Nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <h6>📦 Quản Lý Sản Phẩm</h6>
                            <p class="text-muted small">
                                Thêm, sửa, xóa sản phẩm từ cửa hàng của bạn. Quản lý giá, hình ảnh và danh mục sản phẩm.
                            </p>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">
                                Vào quản lý
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6>🖼️ Quản Lý Banner</h6>
                            <p class="text-muted small">
                                Thêm hoặc xóa banner hiển thị trên trang chủ. Quản lý hình ảnh quảng cáo dễ dàng.
                            </p>
                            <a href="{{ route('admin.banners') }}" class="btn btn-sm btn-outline-info">
                                Vào quản lý
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6>📝 Thông Tin Shop</h6>
                            <p class="text-muted small">
                                Cập nhật thông tin cơ bản của shop: tên, địa chỉ, số điện thoại, email, v.v.
                            </p>
                            <a href="{{ route('admin.shop.edit') }}" class="btn btn-sm btn-outline-secondary">
                                Vào quản lý
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
