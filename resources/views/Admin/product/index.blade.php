@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1>Quản Lý Sản Phẩm</h1>

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

            <div class="mb-3">
                <a href="{{ route('admin.products.create') }}" class="btn btn-success btn-lg">
                    + Thêm Sản Phẩm Mới
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Hình Ảnh</th>
                            <th>Giá Niêm Yết</th>
                            <th>Giá Khuyến Mãi</th>
                            <th>Chiết Khấu</th>
                            <th>Loại</th>
                            <th>Danh Mục</th>
                            <th>Ngày Tạo</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                </td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ $product->image->src }}" alt="{{ $product->name }}" 
                                             style="max-width: 60px; max-height: 60px; border-radius: 4px;">
                                    @else
                                        <span class="text-muted">Không có ảnh</span>
                                    @endif
                                </td>
                                <td>{{ number_format($product->original_price, 0, '.', ',') }} đ</td>
                                <td>
                                    @if($product->sale_price)
                                        {{ number_format($product->sale_price, 0, '.', ',') }} đ
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->discount_percent > 0)
                                        <span class="badge bg-danger">-{{ $product->discount_percent }}%</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $types = ['0' => 'Phụ Kiện', '1' => 'Pin', '2' => 'Điện'];
                                    @endphp
                                    {{ $types[$product->type] ?? 'Không xác định' }}
                                </td>
                                <td>{{ $product->category_id }}</td>
                                <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                       class="btn btn-warning btn-sm">Sửa</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" 
                                          class="d-inline" 
                                          onsubmit="return confirm('Xác nhận xóa sản phẩm này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-5">
                                    Chưa có sản phẩm nào. <a href="{{ route('admin.products.create') }}">Thêm sản phẩm ngay</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                {{ $products->links() }}
            </nav>

            <div class="mt-4">
                <a href="{{ route('admin.shop.edit') }}" class="btn btn-secondary">Thông Tin Shop</a>
                <a href="{{ route('admin.banners') }}" class="btn btn-secondary">Quản Lý Banner</a>
            </div>
        </div>
    </div>
</div>
@endsection
