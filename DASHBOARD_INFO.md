# 🎯 Admin Dashboard

## Truy Cập Dashboard

**URL:** `http://your-domain/admin` hoặc `http://your-domain/admin/`

Bạn sẽ được chuyển hướng tới trang Dashboard chính của admin panel.

---

## Tính Năng Dashboard

### 1. 📊 Statistics Cards (4 thẻ thống kê)

Hiển thị các số liệu nhanh chóng:
- **Tổng Sản Phẩm** - Số lượng sản phẩm trong hệ thống
- **Tổng Banner** - Số lượng banner đang sử dụng
- **Tổng Đơn Hàng** - Số lượng đơn hàng
- **Thông Tin Shop** - Tên shop hiện tại

Mỗi thẻ có link để xem chi tiết hoặc chỉnh sửa.

### 2. 📦 Sản Phẩm Mới Nhất

Hiển thị 5 sản phẩm được tạo gần đây nhất với:
- Hình ảnh sản phẩm (thumbnail)
- Tên sản phẩm
- Giá bán (hiển thị giá khuyến mãi nếu có)
- Ngày tạo
- Nút "Sửa" để chỉnh sửa nhanh

### 3. ⚡ Hành Động Nhanh

Các nút truy cập nhanh đến các tính năng chính:
- **➕ Thêm Sản Phẩm** → `/admin/products/create`
- **🖼️ Quản Lý Banner** → `/admin/banners`
- **📝 Chỉnh Thông Tin Shop** → `/admin/shop/edit`
- **💬 Xem Tư Vấn** → `/admin/consultations`

### 4. 🏪 Thông Tin Shop

Hiển thị thông tin cơ bản của shop đã lưu:
- Tên shop
- Địa chỉ
- Hotline (có link gọi)
- Email (có link gửi email)
- Nút "Chỉnh Sửa Thông Tin"

### 5. 📚 Hướng Dẫn Nhanh

Ba card hướng dẫn nhanh cho các chức năng chính:
- Quản Lý Sản Phẩm
- Quản Lý Banner
- Thông Tin Shop

---

## Sidebar Navigation

Sidebar bên trái cung cấp navigation giữa các chức năng:

**Chính:**
- 🎯 Dashboard

**Quản Lý:**
- 📝 Thông Tin Shop
- 🖼️ Quản Lý Banner
- 📦 Quản Lý Sản Phẩm
- 💬 Tư Vấn

**Khác:**
- 🏠 Trang Chủ

---

## Responsive Design

Dashboard được thiết kế responsive với Bootstrap 5:
- ✅ Thích hợp với desktop
- ✅ Tablet friendly
- ✅ Mobile friendly

---

## Code Implementation

### Controller: `Admin\DashboardController`

```php
public function index()
{
    $stats = [
        'total_products' => Product::count(),
        'total_banners' => Banner::count(),
        'total_orders' => Order::count(),
    ];

    $recentProducts = Product::with('image')->latest()->take(5)->get();
    $shopInfo = Shop::first();

    return view('admin.dashboard', compact('stats', 'recentProducts', 'shopInfo'));
}
```

### View: `resources/views/Admin/dashboard.blade.php`

Hiển thị dashboard với tất cả các card, bảng, và hành động nhanh.

### Route: `routes/web.php`

```php
Route::get('/', [DashboardController::class, 'index'])
    ->name('admin.dashboard');
```

---

## Tiếp Theo

Từ dashboard, bạn có thể:

1. **Xem Thống Kê** - Kiểm tra số lượng sản phẩm, banner, đơn hàng
2. **Xem Sản Phẩm Mới** - Theo dõi sản phẩm vừa tạo
3. **Thêm Sản Phẩm** - Nhanh chóng thêm sản phẩm mới
4. **Quản Lý Banner** - Thêm/xóa banner quảng cáo
5. **Chỉnh Thông Tin Shop** - Cập nhật thông tin cửa hàng
6. **Xem Tư Vấn** - Kiểm tra các tư vấn từ khách hàng

---

## Tips

💡 **Mẹo Sử Dụng:**
- Bạn có thể truy cập trực tiếp bằng cách gõ `/admin` trong URL
- Sidebar tự động highlight trang hiện tại bạn đang xem
- Tất cả action buttons có tooltip khi hover
- Sử dụng responsive sidebar - collapse trên mobile

---

Được cập nhật: **2026-08-04**
