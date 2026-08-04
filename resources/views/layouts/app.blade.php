<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Quản Trị Shop</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        .admin-sidebar {
            background-color: #212529;
            min-height: 100vh;
            padding-top: 20px;
        }
        
        .admin-sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .admin-sidebar a:hover {
            background-color: #343a40;
            border-left-color: #007bff;
        }
        
        .admin-sidebar a.active {
            background-color: #343a40;
            border-left-color: #28a745;
        }
        
        .admin-content {
            padding: 30px;
        }
        
        .page-title {
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <strong>Admin Panel - Quản Trị Shop</strong>
            </a>
            <div class="d-flex align-items-center">
                <span class="text-light me-3">
                    Xin chào, Admin
                </span>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 admin-sidebar">
                <div class="mb-4">
                    <h6 class="text-light px-3 mb-3 text-uppercase" style="font-size: 0.8rem; font-weight: 600; letter-spacing: 1px;">
                        Chính
                    </h6>
                    <a href="{{ route('admin.dashboard') }}" class="@if(request()->routeIs('admin.dashboard')) active @endif">
                        🎯 Dashboard
                    </a>
                </div>

                <div class="mb-4">
                    <h6 class="text-light px-3 mb-3 text-uppercase" style="font-size: 0.8rem; font-weight: 600; letter-spacing: 1px;">
                        Quản Lý
                    </h6>
                    <a href="{{ route('admin.shop.edit') }}" class="@if(request()->routeIs('admin.shop.*')) active @endif">
                        📝 Thông Tin Shop
                    </a>
                    <a href="{{ route('admin.banners') }}" class="@if(request()->routeIs('admin.banners*')) active @endif">
                        🖼️ Quản Lý Banner
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="@if(request()->routeIs('admin.products*')) active @endif">
                        📦 Quản Lý Sản Phẩm
                    </a>
                    <a href="{{ route('admin.consultations') }}" class="@if(request()->routeIs('admin.consultations*')) active @endif">
                        💬 Tư Vấn
                    </a>
                </div>

                <div class="mb-4">
                    <h6 class="text-light px-3 mb-3 text-uppercase" style="font-size: 0.8rem; font-weight: 600; letter-spacing: 1px;">
                        Khác
                    </h6>
                    <a href="{{ route('home') }}">
                        🏠 Trang Chủ
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 admin-content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>
