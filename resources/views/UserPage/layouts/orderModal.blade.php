<div class="checkout-modal" id="checkoutModal">

    <div class="checkout-box">

        <div class="checkout-header">

            <h2>THÔNG TIN ĐẶT HÀNG</h2>

            <button class="close-modal" id="closeModal">&times;</button>

        </div>

        <form id="checkoutForm">
            <div class="form-group">

                <label>Họ và tên <span>*</span></label>

                <input
                    type="text"
                    name="customer_name" value=""
                    placeholder="Nhập họ và tên" required>

            </div>

            <div class="form-group">

                <label>Số điện thoại <span>*</span></label>

                <input
                    type="text"
                    name="phone" value=""
                    placeholder="Nhập số điện thoại" required>

            </div>

            <div class="form-group">

                <label>Địa chỉ giao hàng <span>*</span></label>

                <textarea
                    rows="4"
                    name="address"
                    placeholder="Nhập địa chỉ nhận hàng" required></textarea>

            </div>

            <div class="checkout-summary">

                <span>Tổng thanh toán</span>

                <strong id="modalTotal">

                </strong>

            </div>

            <button class="btn-order">

                XÁC NHẬN ĐẶT HÀNG

            </button>

        </form>

    </div>
</div>

<div class="confirm-modal" id="confirmModal">

    <div class="confirm-box">

        <div class="confirm-icon">
            📦
        </div>

        <h3>Xác nhận đặt hàng?</h3>

        <p>
            Sau khi xác nhận, đơn hàng sẽ được gửi đến cửa hàng để xử lý.
        </p>

        <div class="confirm-total">

            Tổng thanh toán

            <strong id="confirmTotal">
                15.500.000đ
            </strong>

        </div>

        <div class="confirm-action">

            <button
                class="btn-cancel"
                id="cancelConfirm">

                Quay lại

            </button>

            <button
                class="btn-confirm"
                id="acceptOrder">

                Đồng ý đặt hàng

            </button>

        </div>

    </div>

</div>

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
