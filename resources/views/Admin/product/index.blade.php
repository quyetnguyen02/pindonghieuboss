@extends('layouts.app')

@section('content')
<div>
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Quản Lý Sản Phẩm</h1>
            <p class="text-muted small mb-0">Danh sách {{ $products->total() }} sản phẩm</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-success btn-sm">
            + Thêm Mới
        </a>
    </div>

    <form action="{{ route('admin.products.index') }}" method="GET" class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="row gy-3 gx-3 align-items-end">
                <div class="col-12 col-md-5">
                    <label class="form-label">Tìm kiếm sản phẩm</label>
                    <input type="text" name="search" class="form-control"
                           value="{{ request('search') }}"
                           placeholder="Tên sản phẩm hoặc ID">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Giá từ</label>
                    <input type="number" name="price_min" class="form-control"
                           value="{{ request('price_min') }}" min="0" placeholder="0">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label">Giá đến</label>
                    <input type="number" name="price_max" class="form-control"
                           value="{{ request('price_max') }}" min="0" placeholder="0">
                </div>
                <div class="col-12 col-md-1 d-grid">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                </div>
            </div>
        </div>
    </form>

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

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th class="d-none d-sm-table-cell">Giá</th>
                        <th class="d-none d-md-table-cell">Khuyến Mãi</th>
                        <th class="d-none d-lg-table-cell">Loại</th>
                        <th class="d-none d-xl-table-cell">Ngày Tạo</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td><small class="text-muted">{{ $product->id }}</small></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if($product->image)
                                        <img src="{{ asset('/image/' . data_get($product, 'image.src', 'images/no-image.png'))}}" alt="{{ $product->name }}"
                                             style="width: 30px; height: 30px; object-fit: cover; border-radius: 3px;">
                                    @endif
                                    <span class="text-truncate" title="{{ $product->name }}">
                                        {{ Str::limit($product->name, 25) }}
                                    </span>
                                    @if(! $product->visible)
                                        <span class="badge bg-secondary ms-2">Ẩn</span>
                                    @endif
                                </div>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <small>{{ number_format($product->original_price, 0) }}đ</small>
                            </td>
                            <td class="d-none d-md-table-cell">
                                @if($product->sale_price)
                                    <small class="badge bg-info">{{ number_format($product->sale_price, 0) }}đ</small>
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                            <td class="d-none d-lg-table-cell">
                                @php
                                    $types = ['0' => 'Cell Pin', '1' => 'Pin Đóng'];
                                @endphp
                                <small>{{ $types[$product->type] ?? '-' }}</small>
                            </td>
                            <td class="d-none d-xl-table-cell">
                                <small>{{ $product->created_at->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="btn btn-warning btn-sm" title="Sửa">
                                        <small>Sửa</small>
                                    </a>
                                    <form action="{{ route('admin.products.toggle', $product) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('{{ $product->visible ? 'Ẩn sản phẩm này? (sẽ không hiển thị trên trang khách hàng)' : 'Hiển thị lại sản phẩm này?'}}');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $product->visible ? 'btn-warning' : 'btn-success' }}" title="{{ $product->visible ? 'Ẩn' : 'Hiển thị' }}">
                                            <small>{{ $product->visible ? 'Ẩn' : 'Hiển thị' }}</small>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <p class="mb-2">Chưa có sản phẩm nào</p>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
                                    Thêm sản phẩm ngay
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-3 d-flex justify-content-center">
        <nav aria-label="Product pagination">
            {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}
        </nav>
    </div>
</div>
@endsection
