@extends('layouts.app')

@section('content')
<div>
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Quản Lý Đơn Hàng</h1>
            <p class="text-muted small mb-0">Danh sách {{ $orders->total() }} đơn hàng</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-success btn-sm">
            🧾 Làm mới
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small">Tìm kiếm</label>
                    <input name="keyword" value="{{ $keyword }}" type="search" class="form-control form-control-sm" placeholder="ID, tên, số điện thoại">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small">Trạng thái</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        @foreach(App\Models\Order::STATUS_LABELS as $key => $label)
                            <option value="{{ $key }}" @selected((string)$status === (string)$key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small">Khoảng</label>
                    <select name="period" class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option value="week" @selected($period === 'week')>Tuần này</option>
                        <option value="month" @selected($period === 'month')>Tháng này</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small">Từ ngày</label>
                    <input name="from_date" type="date" value="{{ $fromDate }}" class="form-control form-control-sm">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small">Đến ngày</label>
                    <input name="to_date" type="date" value="{{ $toDate }}" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-md-1 text-end">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Lọc</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 small align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>SĐT</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->phone }}</td>
                            <td>{{ number_format($order->total_price, 0) }}đ</td>
                            <td>
                                <span class="badge {{ $order->status_class }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Xem</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                Chưa có đơn hàng phù hợp.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
</div>
@endsection
