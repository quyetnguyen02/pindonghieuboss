@extends('layouts.app')

@section('content')
<div>
    <!-- Page Title -->
    <div class="mb-4">
        <h1 class="h3 h-md-2">Dashboard</h1>
        <p class="text-muted small mb-0">Chào mừng đến với bảng điều khiển quản lý</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 small">Tổng Sản Phẩm</h6>
                            <h3 class="mb-0">{{ $stats['total_products'] }}</h3>
                        </div>
                        <div style="font-size: 2rem; opacity: 0.3;">📦</div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <a href="{{ route('admin.products.index') }}" class="text-decoration-none small">
                        Xem chi tiết →
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 small">Tổng Banner</h6>
                            <h3 class="mb-0">{{ $stats['total_banners'] }}</h3>
                        </div>
                        <div style="font-size: 2rem; opacity: 0.3;">🖼️</div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <a href="{{ route('admin.banners') }}" class="text-decoration-none small">
                        Xem chi tiết →
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 small">Tổng Đơn Hàng</h6>
                            <h3 class="mb-0">{{ $stats['total_orders'] }}</h3>
                        </div>
                        <div style="font-size: 2rem; opacity: 0.3;">🛒</div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <a href="{{ route('admin.orders.index') }}" class="text-decoration-none small">
                        Xem chi tiết →
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-2 small">Shop</h6>
                            <p class="mb-0 small">
                                <strong>{{ Str::limit($shopInfo->shop_name ?? 'Chưa cập nhật', 15) }}</strong>
                            </p>
                        </div>
                        <div style="font-size: 2rem; opacity: 0.3;">🏪</div>
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

    <div class="row g-3">
        <!-- Recent Products -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 small">5 Sản Phẩm Mới Nhất</h5>
                </div>
                <div class="card-body p-0">
                    @if($recentProducts->isEmpty())
                        <div class="p-3 text-center text-muted">
                            <p class="mb-2">Chưa có sản phẩm nào</p>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary">
                                Thêm sản phẩm
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sản Phẩm</th>
                                        <th>Giá</th>
                                        <th class="d-none d-md-table-cell">Ngày Tạo</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentProducts as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($product->image)
                                                        <img src="{{ asset('image/' . $product->image->src) }}" 
                                                             alt="{{ $product->name }}"
                                                             style="width: 35px; height: 35px; object-fit: cover; border-radius: 4px;">
                                                    @endif
                                                    <span class="text-truncate d-block" title="{{ $product->name }}">
                                                        {{ Str::limit($product->name, 20) }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info text-dark">
                                                    {{ number_format($product->sale_price ?? $product->original_price, 0) }}đ
                                                </span>
                                            </td>
                                            <td class="d-none d-md-table-cell">
                                                <small>{{ $product->created_at->format('d/m') }}</small>
                                            </td>
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
                        Xem tất cả
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Info -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 small">Hành Động Nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                            ➕ Thêm Sản Phẩm
                        </a>
                        <a href="{{ route('admin.banners') }}" class="btn btn-info btn-sm">
                            🖼️ Quản Lý Banner
                        </a>
                        <a href="{{ route('admin.shop.edit') }}" class="btn btn-secondary btn-sm">
                            📝 Thông Tin Shop
                        </a>
                        <a href="{{ route('admin.consultations') }}" class="btn btn-warning btn-sm">
                            💬 Xem Tư Vấn
                        </a>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 small">Thông Tin Shop</h5>
                </div>
                <div class="card-body">
                    @if($shopInfo)
                        <div class="mb-3">
                            <small class="text-muted d-block">Tên Shop</small>
                            <p class="mb-0 small"><strong>{{ $shopInfo->shop_name }}</strong></p>
                        </div>
                        @if($shopInfo->address)
                            <div class="mb-3">
                                <small class="text-muted d-block">Địa Chỉ</small>
                                <p class="mb-0 small">{{ Str::limit($shopInfo->address, 30) }}</p>
                            </div>
                        @endif
                        @if($shopInfo->hotline)
                            <div class="mb-3">
                                <small class="text-muted d-block">Hotline</small>
                                <p class="mb-0 small">
                                    <a href="tel:{{ $shopInfo->hotline }}" class="text-decoration-none">
                                        {{ $shopInfo->hotline }}
                                    </a>
                                </p>
                            </div>
                        @endif
                        @if($shopInfo->email)
                            <div class="mb-3">
                                <small class="text-muted d-block">Email</small>
                                <p class="mb-0 small">
                                    <a href="mailto:{{ $shopInfo->email }}" class="text-decoration-none">
                                        {{ Str::limit($shopInfo->email, 25) }}
                                    </a>
                                </p>
                            </div>
                        @endif
                    @else
                        <p class="text-muted small">Chưa cập nhật thông tin shop</p>
                    @endif
                    <hr>
                    <a href="{{ route('admin.shop.edit') }}" class="btn btn-sm btn-outline-primary w-100">
                        Chỉnh Sửa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Guide -->
    <div class="row g-3 mt-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0 small">📚 Hướng Dẫn Nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <h6 class="small">📦 Quản Lý Sản Phẩm</h6>
                            <p class="text-muted small">
                                Thêm, sửa, xóa sản phẩm từ cửa hàng của bạn.
                            </p>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">
                                Vào quản lý
                            </a>
                        </div>
                        <div class="col-12 col-md-4">
                            <h6 class="small">🖼️ Quản Lý Banner</h6>
                            <p class="text-muted small">
                                Thêm hoặc xóa banner hiển thị trên trang chủ.
                            </p>
                            <a href="{{ route('admin.banners') }}" class="btn btn-sm btn-outline-info">
                                Vào quản lý
                            </a>
                        </div>
                        <div class="col-12 col-md-4">
                            <h6 class="small">📝 Thông Tin Shop</h6>
                            <p class="text-muted small">
                                Cập nhật thông tin cơ bản của shop.
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
