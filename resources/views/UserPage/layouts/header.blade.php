<!-- ========== HEADER ========== -->
<header class="header">

    <!-- LOGO -->
    <a href="{{route('home')}}" class="logo">
        <img src="{{asset('image/logo/' . $shop->logo)}}" alt="hieu_boss">
    </a>

    <!-- Ô TÌM KIẾM -->

    <form action="{{ route('search') }}" method="GET" class="search-wrapper">
        <i class="fas fa-search search-icon"></i>
        <input
            type="text"
            name="keyword"
            placeholder="Nhập, tên, Mã sản phẩm, Giá tiền để tìm kiếm"
            aria-label="Tìm kiếm sản phẩm"

            @if($keyword) value="{{$keyword}}" @endif
        >

        <button class="search-btn" type="submit">
            <i class="fas fa-search"></i>
            Tìm kiếm
        </button>
    </form>

    <!-- GIỎ HÀNG -->
    <a href="{{route('card')}}" class="cart-box" style="cursor: pointer;">
        <i class="fas fa-shopping-bag"></i>
        <span class="cart-count" id="cartCount">0</span>
        <span class="cart-text">Giỏ hàng</span>
    </a>

    <!-- Có thể giữ nút DANH MỤC nếu muốn dùng để toggle sidebar -->
    <div class="nav-menu">
        <button class="menu-btn" id="toggleCategoriesBtn" aria-label="Danh mục sản phẩm">
            <i class="fas fa-bars"></i>
            DANH MỤC
        </button>

        <!-- Phần DANH MỤC (bên dưới) -->
        <div class="categories-section" id="categorySection">
            <div class="categories-grid">
                <ul class="categories-list">
                    @if($categories)
                        @foreach($categories as $category)
                            <li>
                                <a href="{{route('search', ['category_id' => $category['id'],'slug' => Str::slug($category['name'])])}}">{{ $category->name }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>

            </div>
        </div>
    </div>



</header>
