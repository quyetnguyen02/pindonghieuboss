<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Quản Trị Shop</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 250px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background-color: #f8f9fa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            z-index: 1030;
        }

        .navbar-toggler {
            border: none;
            color: #fff !important;
            font-size: 1.5rem;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .admin-wrapper {
            display: flex;
            min-height: calc(100vh - 56px);
        }

        .admin-sidebar {
            background-color: #212529;
            width: var(--sidebar-width);
            padding: 20px 0;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
            position: relative;
            z-index: 1020;
        }

        .admin-sidebar.show {
            transform: translateX(0);
        }
        
        .admin-sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            border-left: 3px solid transparent;
            transition: all 0.3s;
            font-size: 0.95rem;
        }
        
        .admin-sidebar a:hover {
            background-color: #343a40;
            border-left-color: #007bff;
            padding-left: 25px;
        }
        
        .admin-sidebar a.active {
            background-color: #343a40;
            border-left-color: #28a745;
        }

        .sidebar-parent {
            position: relative;
        }

        .sidebar-parent > a {
            cursor: pointer;
        }

        .sidebar-submenu {
            display: none;
            margin-left: 1rem;
            border-left: 1px solid rgba(255,255,255,0.12);
            padding-left: 0.5rem;
            margin-top: 0.5rem;
        }

        .sidebar-submenu.show {
            display: block;
        }

        .sidebar-submenu a {
            padding: 8px 20px;
            font-size: 0.9rem;
        }

        .sidebar-submenu a:hover {
            background-color: rgba(255,255,255,0.08);
            border-left-color: transparent;
            padding-left: 20px;
        }

        .admin-sidebar h6 {
            padding-left: 20px;
            padding-right: 20px;
        }
        
        .admin-content {
            flex: 1;
            padding: 20px 15px;
            overflow-y: auto;
        }

        /* Responsive */
        @media (max-width: 768px) {
            :root {
                --sidebar-width: 250px;
            }

            .admin-sidebar {
                position: fixed;
                left: 0;
                top: 56px;
                height: calc(100vh - 56px);
                transform: translateX(-100%);
                width: var(--sidebar-width);
                box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-content {
                width: 100%;
                padding: 15px 10px;
            }

            .navbar-brand {
                font-size: 1rem;
            }
        }

        @media (max-width: 576px) {
            .admin-content {
                padding: 10px 8px;
            }

            .container-fluid, .row {
                --bs-gutter-x: 0.5rem;
            }

            .card {
                margin-bottom: 1rem;
            }

            .btn-sm {
                padding: 0.4rem 0.6rem;
                font-size: 0.8rem;
            }

            .table {
                font-size: 0.85rem;
            }

            .table th, .table td {
                padding: 0.5rem;
            }

            .form-label {
                font-size: 0.9rem;
            }

            .form-control, .form-select {
                font-size: 0.95rem;
                padding: 0.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            h5 {
                font-size: 1rem;
            }
        }

        .page-title {
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: box-shadow 0.3s;
        }

        .card:hover {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        /* Scrollbar styling */
        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background: #343a40;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: #555;
            border-radius: 3px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: #777;
        }

        /* Responsive table */
        .table-responsive {
            border-radius: 0.25rem;
        }

        @media (max-width: 768px) {
            .table {
                font-size: 0.8rem;
            }

            .table th, .table td {
                white-space: nowrap;
            }
        }

        /* Form responsive */
        @media (max-width: 768px) {
            .row > [class*='col-'] {
                margin-bottom: 1rem;
            }
        }

        /* Badge responsive */
        .badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <button class="navbar-toggler d-lg-none" type="button" id="sidebarToggle" aria-label="Toggle sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">
                <strong>Admin Shop</strong>
            </a>
            <div class="d-flex align-items-center gap-2">
                <span class="text-light me-2 d-none d-sm-inline">Admin</span>
            </div>
        </div>
    </nav>

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <div class="admin-sidebar" id="adminSidebar">
            <div class="mb-4">
                <h6 class="text-light mb-3 text-uppercase">Chính</h6>
                <a href="{{ route('admin.dashboard') }}" class="@if(request()->routeIs('admin.dashboard')) active @endif">
                    🎯 Dashboard
                </a>
            </div>

            <div class="mb-4">
                <h6 class="text-light mb-3 text-uppercase">Quản Lý</h6>
                <a href="{{ route('admin.shop.edit') }}" class="@if(request()->routeIs('admin.shop.*')) active @endif">
                    📝 Thông Tin Shop
                </a>
                <a href="{{ route('admin.banners') }}" class="@if(request()->routeIs('admin.banners*')) active @endif">
                    🖼️ Banner
                </a>
                <div class="sidebar-parent">
                    <a href="#" id="productMenuToggle" class="@if(request()->routeIs('admin.products*') || request()->routeIs('admin.category-display*') || request()->routeIs('admin.cell-types*')) active @endif">
                        📦 Sản Phẩm
                    </a>
                    <div class="sidebar-submenu @if(request()->routeIs('admin.products*') || request()->routeIs('admin.category-display*') || request()->routeIs('admin.cell-types*')) show @endif">
                        <a href="{{ route('admin.products.index') }}" class="@if(request()->routeIs('admin.products*')) active @endif">Danh sách</a>
                        <a href="{{ route('admin.category-display.edit') }}" class="@if(request()->routeIs('admin.category-display*')) active @endif">Danh mục</a>
                        <a href="{{ route('admin.cell-types.index') }}" class="@if(request()->routeIs('admin.cell-types*')) active @endif">Thương hiệu Cell</a>
                    </div>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="@if(request()->routeIs('admin.orders*')) active @endif">
                    🧾 Đơn Hàng
                </a>
                <a href="{{ route('admin.consultations') }}" class="@if(request()->routeIs('admin.consultations*')) active @endif">
                    💬 Tư Vấn
                </a>
            </div>

            <div class="mb-4">
                <h6 class="text-light mb-3 text-uppercase">Khác</h6>
                <a href="{{ route('home') }}">
                    🏠 Trang Chủ
                </a>

                @auth
                    <form method="POST" action="{{ route('admin.logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-light w-100 text-start">
                            🔓 Đăng xuất
                        </button>
                    </form>
                @endauth
            </div>
        </div>

        <!-- Main Content -->
        <div class="admin-content">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('adminSidebar');
            sidebar.classList.toggle('show');
        });

        // Close sidebar when link is clicked on mobile
        const sidebarLinks = document.querySelectorAll('.admin-sidebar a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    document.getElementById('adminSidebar').classList.remove('show');
                }
            });
        });

        const productMenuToggle = document.getElementById('productMenuToggle');
        const productSubmenu = document.querySelector('.sidebar-submenu');

        if (productMenuToggle) {
            productMenuToggle.addEventListener('click', function(event) {
                event.preventDefault();
                productSubmenu?.classList.toggle('show');
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('adminSidebar');
            const toggle = document.getElementById('sidebarToggle');
            if (window.innerWidth < 768 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
