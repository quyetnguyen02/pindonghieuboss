# Hướng Dẫn Sử Dụng Admin Panel

## Giới Thiệu
Admin panel cung cấp các chức năng quản lý toàn bộ thông tin shop bao gồm:
- Quản lý thông tin shop
- Quản lý banner
- Quản lý sản phẩm (thêm, sửa, xóa)

## Truy Cập Admin Panel

Admin panel có thể truy cập tại:
```
http://your-domain.com/admin
```

## Các Tính Năng

### 1. Quản Lý Thông Tin Shop

**URL:** `/admin/shop/edit`

Cho phép chỉnh sửa các thông tin cơ bản của shop:
- Tên shop
- Logo (URL)
- Địa chỉ
- Hotline
- Zalo
- Email
- Fanpage Facebook

**Cách sử dụng:**
1. Điều hướng đến trang "Thông Tin Shop"
2. Nhập/chỉnh sửa các thông tin
3. Nhấn "Cập Nhật Thông Tin"

---

### 2. Quản Lý Banner

**URL:** `/admin/banners`

Cho phép thêm, xóa banner hiển thị trên trang chủ.

**Các tác vụ:**

#### Thêm Banner Mới
1. Nhập URL của hình ảnh banner
2. Nhấn "Thêm Banner"

#### Xóa Banner
1. Tìm banner cần xóa trong danh sách
2. Nhấn "Xóa"
3. Xác nhận khi được yêu cầu

---

### 3. Quản Lý Sản Phẩm

**URL:** `/admin/products`

Cho phép thêm, sửa, xóa sản phẩm trong hệ thống.

#### Danh Sách Sản Phẩm
- Hiển thị tất cả sản phẩm với thông tin:
  - ID sản phẩm
  - Tên sản phẩm
  - Hình ảnh
  - Giá niêm yết
  - Giá khuyến mãi
  - Phần trăm chiết khấu
  - Loại sản phẩm
  - Danh mục

#### Thêm Sản Phẩm Mới

**URL:** `/admin/products/create`

1. Nhấn "+ Thêm Sản Phẩm Mới"
2. Điền các thông tin:
   - **Tên Sản Phẩm** (bắt buộc)
   - **Danh Mục** (bắt buộc)
   - **Hình Ảnh Chính** (bắt buộc)
   - **Giá Niêm Yết** (bắt buộc)
   - **Giá Khuyến Mãi** (tùy chọn)
   - **Loại Sản Phẩm** (bắt buộc):
     - Phụ Kiện
     - Pin
     - Điện
3. Nhấn "Thêm Sản Phẩm"

#### Chỉnh Sửa Sản Phẩm

**URL:** `/admin/products/{product}/edit`

1. Nhấn "Sửa" trên hàng sản phẩm cần chỉnh sửa
2. Thay đổi các thông tin cần thiết
3. Nhấn "Cập Nhật Sản Phẩm"

#### Xóa Sản Phẩm

Có 2 cách xóa sản phẩm:

**Cách 1: Từ danh sách**
1. Nhấn "Xóa" trên hàng sản phẩm cần xóa
2. Xác nhận khi được yêu cầu

**Cách 2: Từ trang chỉnh sửa**
1. Truy cập trang chỉnh sửa sản phẩm
2. Cuộn xuống phần "Xóa Sản Phẩm"
3. Nhấn "Xóa Sản Phẩm"
4. Xác nhận khi được yêu cầu

> ⚠️ **Cảnh báo:** Xóa sản phẩm không thể hoàn tác!

---

## Database Schema

### Shop Info Table (`shop_info_p`)
```sql
- id: INT (Primary Key)
- shop_name: VARCHAR(255)
- logo: VARCHAR(255)
- address: VARCHAR(255)
- hotline: VARCHAR(20)
- zalo: VARCHAR(20)
- email: VARCHAR(255)
- fanpage: TEXT
- created_at: TIMESTAMP
- updated_at: TIMESTAMP
```

### Banners Table (`banners_p`)
```sql
- id: INT (Primary Key)
- src: VARCHAR(255) - URL hình ảnh
- created_at: TIMESTAMP
- updated_at: TIMESTAMP
```

### Products Table (`products_p`)
```sql
- id: INT (Primary Key)
- name: VARCHAR(255)
- image_id: BIGINT (FK to thumbs_p)
- category_id: BIGINT (FK to categories_p)
- original_price: BIGINT
- sale_price: BIGINT
- type: ENUM('0', '1', '2')
  - '0': Phụ Kiện
  - '1': Pin
  - '2': Điện
- thumb_id: JSON
- created_at: TIMESTAMP
- updated_at: TIMESTAMP
```

---

## Controllers

### ShopController
- `editShopInfo()` - Hiển thị form chỉnh sửa thông tin shop
- `updateShopInfo()` - Cập nhật thông tin shop
- `manageBanners()` - Hiển thị danh sách banner
- `storeBanner()` - Thêm banner mới
- `deleteBanner()` - Xóa banner

### ProductController
- `index()` - Hiển thị danh sách sản phẩm
- `create()` - Hiển thị form tạo sản phẩm
- `store()` - Lưu sản phẩm mới
- `edit()` - Hiển thị form chỉnh sửa sản phẩm
- `update()` - Cập nhật sản phẩm
- `destroy()` - Xóa sản phẩm

---

## Routes

### Shop Management
- `GET /admin/shop/edit` - admin.shop.edit
- `PUT /admin/shop/update` - admin.shop.update

### Banner Management
- `GET /admin/banners` - admin.banners
- `POST /admin/banners` - admin.banners.store
- `DELETE /admin/banners/{banner}` - admin.banners.delete

### Product Management
- `GET /admin/products` - admin.products.index
- `GET /admin/products/create` - admin.products.create
- `POST /admin/products` - admin.products.store
- `GET /admin/products/{product}/edit` - admin.products.edit
- `PUT /admin/products/{product}` - admin.products.update
- `DELETE /admin/products/{product}` - admin.products.destroy

---

## Lưu Ý

1. **Validation:** Tất cả các form đều có validation. Nếu có lỗi, hệ thống sẽ hiển thị thông báo lỗi chi tiết.

2. **Upload Hình Ảnh:** Hiện tại, hình ảnh được lưu trữ dưới dạng URL. Hãy đảm bảo URL là hợp lệ.

3. **Pagination:** Danh sách sản phẩm được phân trang mỗi 20 sản phẩm.

4. **Kiểm Tra Giá:** Khi nhập giá, hãy đảm bảo giá khuyến mãi nhỏ hơn giá niêm yết để hiển thị chiết khấu chính xác.

---

## Các Lỗi Thường Gặp

### "Không thể truy cập trang admin"
- Kiểm tra xem bạn đã chạy migration chưa: `php artisan migrate`
- Kiểm tra xem routes đã được đăng ký chưa: `php artisan route:list --path=admin`

### "Lỗi validation"
- Kiểm tra các trường bắt buộc đã được điền chưa
- Kiểm tra định dạng email nếu có lỗi email

### "Không thể thêm/cập nhật"
- Kiểm tra các trường required (bắt buộc)
- Kiểm tra xem danh mục và hình ảnh có tồn tại không

---

## Support

Nếu gặp vấn đề, vui lòng liên hệ với team phát triển.
