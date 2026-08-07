@extends('layouts.app')

@section('content')
<div>
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Danh sách khách cần tư vấn</h1>
            <p class="text-muted small mb-0">Danh sách {{ $consultations->total() }} yêu cầu</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.consultations') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small">Tìm kiếm</label>
                    <input name="keyword" value="{{ $keyword ?? '' }}" type="search" class="form-control form-control-sm" placeholder="ID, tên, số điện thoại, sản phẩm">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small">Trạng thái</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option value="0" @selected((string)($status ?? '') === '0')>Chờ tư vấn</option>
                        <option value="1" @selected((string)($status ?? '') === '1')>Đã tư vấn</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small">Khoảng</label>
                    <select name="period" class="form-select form-select-sm">
                        <option value="">Tất cả</option>
                        <option value="week" @selected(($period ?? '') === 'week')>Tuần này</option>
                        <option value="month" @selected(($period ?? '') === 'month')>Tháng này</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small">Từ ngày</label>
                    <input name="from_date" type="date" value="{{ $fromDate ?? '' }}" class="form-control form-control-sm">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small">Đến ngày</label>
                    <input name="to_date" type="date" value="{{ $toDate ?? '' }}" class="form-control form-control-sm">
                </div>
                <div class="col-12 col-md-1 text-end">
                    <button type="submit" class="btn btn-primary btn-sm w-100">Lọc</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Web</th>
                        <th>Khách hàng</th>
                        <th>SĐT</th>
                        <th>Sản phẩm</th>
                        <th>Ngày gửi</th>
                        <th>Trạng thái</th>
                        <th width="120">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($consultations as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>@if($item->web === 1) Pin Đóng Hiếu Boss @else Hukan VN @endif</td>
                            <td>{{ $item->customer_name }}</td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ $item->product }}</td>
                            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($item->status==0)
                                    <span class="status waiting">Chờ tư vấn</span>
                                @else
                                    <span class="status done">Đã tư vấn</span>
                                @endif
                            </td>
                            <td>
                                @if($item->status == 0)
                                    <form action="{{ route('admin.consultations.done', $item->id) }}" method="POST" onsubmit="return confirm('Xác nhận đã tư vấn khách hàng này?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">✓ Đã tư vấn</button>
                                    </form>
                                @else
                                    <span class="badge success">✓ Đã tư vấn</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Chưa có dữ liệu phù hợp.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $consultations->links() }}
    </div>
</div>
@endsection
