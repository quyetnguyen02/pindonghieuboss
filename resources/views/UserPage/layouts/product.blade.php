<section class="product-section">

    <div class="section-header">
        <div class="section-title">
            {{$categories[$category_id]->name}}
        </div>

        <a href="{{route('search', ['category_id' => $category_id,'slug' => Str::slug($categories[$category_id]->name)])}}" class="view-all-top">
            >>Xem tất cả
        </a>
    </div>

    <div class="product-grid">

        @foreach($products[$category_id] as $product)
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
                            Liên Hệ Ngay {{$shop->zalo}}
                        @endif

                    </div>
                </a>

            </div>

        @endforeach

    </div>

    <div class="section-footer">
        <a href="{{route('search', ['category_id' => $category_id,'slug' => Str::slug($categories[$category_id]->name)])}}" class="view-all-btn">
            Xem tất cả {{$categories[$category_id]->name}}
        </a>
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

{{--                <div class="product-option">--}}
{{--                    <button class="option-btn active">Thân máy</button>--}}
{{--                    --}}{{--                            <button class="option-btn">Bộ 1 Pin</button>--}}
{{--                </div>--}}
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
</section>
