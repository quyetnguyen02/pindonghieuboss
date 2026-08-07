@extends('UserPage.layouts.app')

@section('title', 'Tìm kiếm')

@section('content')
    <section class="cart-page">

        <div class="cart-title">
            <h2>GIỎ HÀNG CỦA BẠN</h2>
        </div>

        <div class="cart-table">

            <div class="cart-header">

                <div>Ảnh</div>
                <div>Mã SP</div>
                <div>Tên sản phẩm</div>
                <div>Số lượng</div>
                <div>Đơn giá</div>
                <div>Thành tiền</div>
                <div>Xóa</div>

            </div>

            <!-- Item -->

            <div class="" id="cartItems">

            </div>

        </div>


        <div class="cart-total">

            <div class="line">
                <span>Tạm tính</span>
                <b id="subTotal">0đ</b>
            </div>

            <div class="line">
                <span>Phí vận chuyển</span>
                <b>Miễn phí</b>
            </div>

            <div class="line total">
                <span>Tổng cộng</span>
                <b id="totalPrice">0đ</b>
            </div>

            <div class="cart-btn">

                <a href="{{route('search', ['keyword' => ''])}}" class="back">
                    Tiếp tục mua
                </a>

                <button class="checkout" id="checkout">
                    Đặt Hàng
                </button>

            </div>

        </div>

    </section>

@include('UserPage.layouts.orderModal')
@endsection

@push('scripts')
    @vite([
    'resources/js/UserPage/card.js',
    'resources/js/UserPage/orderModal.js',
    ])
@endpush
