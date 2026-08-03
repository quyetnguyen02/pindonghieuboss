
    <!-- Container bên ngoài (ẩn ban đầu) -->
    <div id="categoriesContainer" class="categories-container">
        <!-- Phần SLIDER (full-width) -->
        <div class="slider-full" id="sliderSection">
            <div class="slider-wrapper">
                <div class="slides">
                    @foreach($banners as $banner)
                        <div class="slide"><img src="{{ asset('image/' . $banner->src) }}" alt="hukan"></div>
                    @endforeach
                </div>
            </div>
            <div class="slider-dots">
                @foreach($banners as $index => $banner)

                    <span class="dot @if($index == 0) active @endif" data-index="{{$index}}"></span>

                @endforeach
            </div>
        </div>


    </div>

