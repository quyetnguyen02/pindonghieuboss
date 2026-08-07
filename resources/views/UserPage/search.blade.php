@extends('UserPage.layouts.app')

@section('title', 'Tìm kiếm')

@section('content')
    <section class="search-page">

        <div class="search-container">

            <!-- LEFT -->
            <section class="product-section">
                <div class="product-grid">

                    @foreach($products as $product)
                        <div class="product-card">

                            <div class="product-image">

                                <img src="{{ asset('/image/' . data_get($product, 'image.src', 'images/no-image.png'))}}">

                                @if($product['discount_percent'] > 0)
                                    <span class="discount">-{{$product['discount_percent']}}%</span>
                                @endif

                                <button class="quick-view-btn"
                                        data-name="{{ $product['name'] }}"
                                        data-image="{{ asset('/image/' . data_get($product,'image.src','images/no-image.png')) }}"
                                        data-original="{{ $product['original_price'] }}"
                                        data-sale="{{ $product['sale_price'] }}"
                                        data-id = "{{$product['id']}}"
                                        data-sku = "{{$product['sku']}}"
                                        data-cate = "{{$product['category_id']}}">
                                    XEM NHANH
                                </button>

                            </div>

                            <a href="{{ route('product-detail', [
                                'product' => $product['id'],
                                'slug' => Str::slug($product['name'])
                            ]) }}">
                                <div class="product-name">
                                    {{$product['name']}}
                                </div>

                                <div class="price">
                                    @if($product['original_price'] !== 0)
                                        @if($product['sale_price'])
                                            <span class="old-price">{{number_format($product['original_price'])}}đ</span>
                                            <span class="new-price">{{number_format($product['sale_price'])}}đ</span>
                                        @else
                                            <span class="new-price">{{number_format($product['original_price'])}}đ</span>
                                        @endif
                                    @else
                                        Liên Hệ Ngay {{$shop->hotline}}
                                    @endif
                                </div>
                            </a>
                        </div>

                    @endforeach


                </div>
                <div class="pagination-wrapper">
                    {{ $products->links('vendor.pagination.default') }}
                </div>

                <div class="quick-modal" id="quickModal">
                    <div class="modal-content">
                        <span class="close-modal">&times;</span>
                        <input type="hidden" id="skuModal" value="">
                        <input type="hidden" id="categoryId" value="">


                        <div class="modal-left">
                            <img id="modalImage">
                        </div>

                        <div class="modal-right">
                            <h1 id="modalTitle"></h1>

{{--                            <div class="product-option">--}}
{{--                                <button class="option-btn active">Thân máy</button>--}}
{{--                                --}}{{--                            <button class="option-btn">Bộ 1 Pin</button>--}}
{{--                            </div>--}}
                            <div class="product-price">
                                <span class="old-price" id="modalOldPrice"></span>
                                <span class="new-price" id="modalNewPrice"></span>
                            </div>
                            <div style="display: flex; gap: 10px">
                                <button class=" buy-now add-card" id="btnAddCart" data-id="" style="font-size: 17px">

                                    Thêm Giỏ Hàng

                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </section>


            <!-- RIGHT -->

            <form action="{{ route('search') }}" class="filter-sidebar" method="GET">

                {{-- Giữ keyword nếu có --}}
                <input type="hidden" name="keyword" value="{{ $keyword }}">
                <aside class="filter-sidebar">

                    <div class="filter-header">
                        Bộ lọc
                    </div>

                    {{-- MỨC GIÁ --}}
                    <div class="filter-group">

                        <h4>MỨC GIÁ</h4>

                        <label>
                            <input
                                type="radio"
                                name="price"
                                value="0-500000"
                                {{ request('price') == '0-500000' ? 'checked' : '' }}>
                            0đ - 500.000đ
                        </label>

                        <label>
                            <input
                                type="radio"
                                name="price"
                                value="500000-1000000"
                                {{ request('price') == '500000-1000000' ? 'checked' : '' }}>
                            500.000đ - 1.000.000đ
                        </label>

                        <label>
                            <input
                                type="radio"
                                name="price"
                                value="1000000-2000000"
                                {{ request('price') == '1000000-2000000' ? 'checked' : '' }}>
                            1.000.000đ - 2.000.000đ
                        </label>
{{--                        <label>--}}
{{--                            <input--}}
{{--                                type="radio"--}}
{{--                                name="price"--}}
{{--                                value="2000000-max"--}}
{{--                                {{ request('price') == '2000000-max' ? 'checked' : '' }}>--}}
{{--                            Trên 2.000.000đ--}}
{{--                        </label>--}}
                        <label>
                            <input
                                type="radio"
                                name="price"
                                value=""
                                {{ request('price') == '' ? 'checked' : '' }}>
                            Tất Cả
                        </label>

                    </div>


                    <div class="filter-group">

                        <h4>Số Lượng Cell</h4>

                        @foreach($cells as $cell)

                            <label>

                                <input
                                    type="checkbox"
                                    name="cell[]"
                                    value="{{ $cell }}"
                                    {{ in_array($cell, request('cell', [])) ? 'checked' : '' }}>

                                {{ $cell }} Cell

                            </label>

                        @endforeach

                    </div>

                    <div class="filter-group">

                        <h4>Loại Cell Pin</h4>

                        @foreach($cell_type as $key => $value)

                            <label>

                                <input
                                    type="checkbox"
                                    name="type[]"
                                    value="{{ $key }}"
                                    {{ in_array($key, request('type', [])) ? 'checked' : '' }}>

                                {{ $value }}

                            </label>

                        @endforeach

                    </div>

                    <div class="filter-action">
                        <button type="submit" class="filter-btn">
                            <i class="fas fa-filter"></i>
                            Áp dụng
                        </button>
                    </div>

                </aside>
            </form>

        </div>
    </section>
@endsection
@push('scripts')
    @vite('resources/js/UserPage/home.js')
@endpush

