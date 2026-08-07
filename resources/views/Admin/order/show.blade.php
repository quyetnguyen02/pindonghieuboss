@extends('layouts.app')

@section('content')
<div>
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Chi Tiết Đơn Hàng #{{ $order->id }}</h1>
            <p class="text-muted small mb-0">Thông tin đơn hàng và trạng thái</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">← Quay lại</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <h5 class="mb-2">Thông tin khách hàng</h5>
                    <p class="mb-1"><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
                    <p class="mb-1"><strong>SĐT:</strong> {{ $order->phone }}</p>
                    <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                </div>
                <div class="col-12 col-md-6">
                    <h5 class="mb-2">Tóm tắt đơn hàng</h5>
                    <p class="mb-1"><strong>Trạng thái:</strong> <span class="badge {{ $order->status_class }}">{{ $order->status_label }}</span></p>
                    <p class="mb-1"><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0) }}đ</p>
                    <p class="mb-1"><strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-3">Sản phẩm trong đơn</h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->product->name ?? '---' }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>{{ number_format($item->price, 0) }}đ</td>
                                <td>{{ number_format($item->price * $item->qty, 0) }}đ</td>
                                <td>
                                    @if($order->can_edit_items)
                                        <form action="{{ route('admin.orders.items.remove', ['order' => $order, 'item' => $item]) }}" method="POST" onsubmit="return confirm('Xóa sản phẩm này khỏi đơn hàng?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                        </form>
                                    @else
                                        <span class="text-muted small">Không cho phép</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-3">Thêm sản phẩm vào đơn</h5>
            @if($order->can_edit_items)
                <form action="{{ route('admin.orders.items.add', $order) }}" method="POST" class="row g-3 align-items-end">
                    @csrf
                    <div class="col-12 col-md-5">
                        <label class="form-label small">Sản phẩm</label>
                        <select name="product_id" class="form-select form-select-sm" required>
                            <option value="">Chọn sản phẩm</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} - {{ number_format($product->sale_price ?: $product->original_price, 0) }}đ</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label small">Số lượng</label>
                        <input type="number" name="qty" min="1" value="1" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-12 col-md-2">
                        <button type="submit" class="btn btn-success btn-sm w-100">Thêm vào đơn</button>
                    </div>
                </form>
            @else
                <div class="alert alert-warning mb-0">
                    Không thể thêm sản phẩm cho đơn hàng ở trạng thái này.
                </div>
            @endif
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.orders.status.update', $order) }}" method="POST" class="row g-3 align-items-end">
                @csrf
                @method('PUT')
                <div class="col-12 col-md-4">
                    <label class="form-label small">Cập nhật trạng thái</label>
                    <select name="status" class="form-select form-select-sm">
                        @foreach(App\Models\Order::STATUS_LABELS as $key => $label)
                            <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Lưu trạng thái</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
