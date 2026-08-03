<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>@yield('title', 'BOSHUN')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    @vite('resources/css/app.css')
</head>
<body>
<div class="consultation-page">

    <div class="page-header">

        <h2>Danh sách khách cần tư vấn</h2>

        <div class="filter-tabs">

            <a href="{{ route('admin.consultations') }}"
               class="{{ request('status')===null ? 'active' : '' }}">
                Tất cả
            </a>

            <a href="{{ route('admin.consultations',['status'=>0]) }}"
               class="{{ request('status')==='0' ? 'active' : '' }}">
                Chờ tư vấn
            </a>

            <a href="{{ route('admin.consultations',['status'=>1]) }}"
               class="{{ request('status')==='1' ? 'active' : '' }}">
                Đã tư vấn
            </a>

        </div>

    </div>

    <table class="consultation-table">

        <thead>

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

        @foreach($consultations as $item)

            <tr>

                <td>{{ $item->id }}</td>
                <td>@if($item->web === 1) Pin Đóng Hiếu Boss @else Hukan VN @endif</td>

                <td>{{ $item->customer_name }}</td>

                <td>{{ $item->phone }}</td>

                <td>{{ $item->product }}</td>

                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>

                <td>

                    @if($item->status==0)

                        <span class="status waiting">
                            Chờ tư vấn
                        </span>

                    @else

                        <span class="status done">
                            Đã tư vấn
                        </span>

                    @endif

                </td>

                <td>
                    @if($item->status == 0)

                        <form action="{{ route('admin.consultations.done', $item->id) }}"
                              method="POST"
                              onsubmit="return confirm('Xác nhận đã tư vấn khách hàng này?')">

                            @csrf

                            <button type="submit" class="btn-consulted">
                                ✓ Đã tư vấn
                            </button>

                        </form>

                    @else

                        <span class="badge success">
                            ✓ Đã tư vấn
                        </span>

                    @endif
                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

</div>
</body>
</html>
