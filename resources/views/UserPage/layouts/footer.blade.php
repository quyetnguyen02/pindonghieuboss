<footer class="footer">

    <div class="footer-top">

        <!-- Công ty -->
        <div class="footer-col">

            <h3>{{$shop->shop_name}}</h3>

            <p>
                <strong>Địa chỉ:</strong>
                {{$shop->address}}
            </p>

            <p>
                <strong>Hotline:</strong>
                {{\App\Models\Shop::formatPhoneNumber($shop->hotline)}}
            </p>

            <p>
                <strong>Email:</strong>
                {{$shop->email}}
            </p>

        </div>

        <!-- Hỗ trợ -->
        <div class="footer-col footer-2">

            <h3>HỖ TRỢ KHÁCH HÀNG</h3>

            <ul>

                <li><a href="#">Phương Thức Thanh Toán</a></li>

                <li><a href="#">Chính Sách Vận Chuyển</a></li>

                <li><a href="#">Chính Sách Quyền Riêng Tư</a></li>

                <li><a href="#">Chính Sách Bảo Hành</a></li>

                <li><a href="#">Điều Khoản Và Điều Kiện</a></li>

                <li><a href="#">Câu Hỏi Thường Gặp</a></li>

                <li><a href="#">Trung Tâm Trợ Giúp</a></li>

            </ul>

        </div>

        <!-- Fanpage -->
        <div class="footer-col">

            <h3>KẾT NỐI VỚI CHÚNG TÔI</h3>

            <div class="fanpage">
                <div class="fb-page"
                     data-href="{{$shop->fanpage}}"
                     data-width="340"
                     data-height="130"
                     data-hide-cover="false"
                     data-show-facepile="false"></div>
            </div>

            <div class="socials">

                @if($shop->fanpage)
                    <a href="{{$shop->fanpage}}">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                @endif


                @if($shop->email)
                    <a href="{{'mailto:' . $shop->email}}">
                        <i class="fas fa-envelope"></i>
                    </a>
                @endif

                @if($shop->hotline)
                    <a href="{{'tel:' . $shop->hotline}}">
                        <i class="fas fa-phone"></i>
                    </a>
                @endif


            </div>

        </div>

    </div>
</footer>

<div class="floating-contact">
    @if($shop->zalo)
        <a
            href="{{'https://zalo.me/' . $shop->zalo}}"
            target="_blank"
            class="contact-btn zalo">

            <img src="{{asset('image/logo/zalo.svg')}}" alt="Zalo">

        </a>
    @endif

    @if($shop->hotline)
        <a
            href="{{'tel:' . $shop->hotline}}"
            class="contact-btn phone">
            <i class="fa-solid fa-phone"></i>
        </a>
    @endif

</div>

<!-- Button báo giá -->
<button class="quote-btn" id="openQuote" style="animation: 2s infinite shake;">
    NHẬN BÁO GIÁ
</button>

<div class="popup-overlay" id="quoteModal">
    <div class="popup-modal">

        <button class="popup-close">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="popup-header">
            <h2>ĐĂNG KÝ TƯ VẤN BÁO GIÁ</h2>
            <p>Vui lòng để lại thông tin, chúng tôi sẽ liên hệ lại ngay!</p>
        </div>

        <div class="popup-banner">
            <img src="{{ asset('image/z6459770787932_32ef601e85b3ef311605fc825bcd5c15.jpg') }}" alt="">
        </div>

        <form id="consultForm" class="popup-form">
            @csrf
            <input
                type="text"
                name="customer_name"
                placeholder="Họ tên"
            >

            <input
                type="text"
                name="phone"
                placeholder="Số điện thoại"
            >

            <input
                type="text"
                name="product"
                placeholder="Loại Sản Phẩm"
            >

            <button type="submit">
                NHẬN TƯ VẤN
            </button>
        </form>

        <div class="notify-modal" id="notifyModal">

            <div class="notify-box">

                <div class="notify-icon" id="notifyIcon">
                    ✓
                </div>

                <h3 id="notifyTitle"></h3>

                <p id="notifyMessage"></p>

                <button id="notifyBtn">
                    Đóng
                </button>

            </div>

        </div>

    </div>
</div>
