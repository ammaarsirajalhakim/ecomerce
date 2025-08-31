@extends('layouts.app')
@section('content')
<main>

    @if (isset($slides) && !$slides->isEmpty())
        <section class="swiper-container js-swiper-slider swiper-number-pagination slideshow"
            data-settings='{
                "autoplay": {
                "delay": 5000
                },
                "slidesPerView": 1,
                "effect": "fade",
                "loop": true
            }'>
            <div class="swiper-wrapper">
                @foreach ($slides as $slide)
                    <div class="swiper-slide">
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-6 col-md-6 text-center">
                                    <img loading="lazy" src="{{ asset('uploads/slides') }}/{{ $slide->image }}"
                                        alt="{{ $slide->image }}"
                                        class="img-fluid slideshow-character__img animate animate_fade animate_btt animate_delay-9" />
                                    <div class="character_markup type2 mt-2 d-none d-md-block">
                                        <p
                                            class="text-uppercase font-sofia mark-grey-color animate animate_fade animate_btt animate_delay-10 mb-0">
                                            {{ $slide->tagline }}
                                        </p>
                                    </div>
                                </div>

                                <div class="col-6 col-md-6 slideshow-text text-start">
                                    <h6
                                        class="text_dash text-uppercase fw-medium animate animate_fade animate_btt animate_delay-3 mb-2">
                                        Produk Unggulan
                                    </h6>
                                    <h2 class="fw-normal animate animate_fade animate_btt animate_delay-5 mb-1">
                                        {{ $slide->title }}
                                    </h2>
                                    <h2 class="fw-bold animate animate_fade animate_btt animate_delay-5 mb-3">
                                        {{ $slide->subtitle }}
                                    </h2>
                                    <a href="{{ $slide->link }}"
                                        class="btn btn-primary px-3 py-2 animate animate_fade animate_btt animate_delay-7">
                                        Belanja Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="container">
                <div
                    class="slideshow-pagination slideshow-number-pagination d-flex align-items-center justify-content-center justify-content-md-start position-absolute bottom-0 mb-5">
                </div>
            </div>
        </section>
    @endif

    <style>
        /* CSS untuk harga */
        .product-card__price .price-new {
            color: #d9534f;
            font-weight: bold;
            font-size: 1.1em;
            margin-right: 8px;
        }

        .product-card__price .price-old {
            color: #8c8c8c;
            text-decoration: line-through;
        }

        /* CSS untuk Coverflow Slider "Mungkin Kamu Suka" */
        .swiper-coverflow-wrapper {
            overflow: hidden;
        }

        .category-coverflow .swiper-slide {
            transition: transform 0.4s ease;
            transform: scale(0.95);
            -webkit-backface-visibility: hidden; /* Mencegah rendering blur pada Safari/Chrome */
            backface-visibility: hidden; /* Mencegah rendering blur */
            -webkit-font-smoothing: antialiased; /* Membantu rendering teks lebih jelas */
        }

        .category-coverflow .swiper-slide-active {
            transform: scale(1);
            -webkit-backface-visibility: hidden; /* Mencegah rendering blur pada Safari/Chrome */
            backface-visibility: hidden; /* Mencegah rendering blur */
            -webkit-font-smoothing: antialiased; /* Membantu rendering teks lebih jelas */
        }

        .category-coverflow-item {
            display: block;
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            height: 280px;
            background-color: #f0f0f0;
        }

        .category-coverflow-item img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.4s ease;
            image-rendering: -webkit-optimize-contrast; /* Webkit browsers */
            image-rendering: crisp-edges; /* Modern browsers */
        }

        .category-coverflow-item:hover img {
            transform: scale(1.05);
        }

        .img-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    background: transparent;
}

.img-overlay::before,
.img-overlay::after {
    content: "";
    position: absolute;
    top: 0;
    width: 10%;   /* lebar sisi putih */
    height: 100%;
    background: white;
}

.img-overlay::before {
    left: 0;  /* sisi kiri */
}

.img-overlay::after {
    right: 0; /* sisi kanan */
}


        .slide-content {
            position: absolute;
            bottom: 20px;
            left: 20px;
            color: white;
            text-align: left;
        }
        
        .slide-content .tagline {
            font-size: 0.8rem;
            margin-bottom: 2px;
        }

        .slide-content .title {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        /* CSS Lainnya */
        .slideshow .slideshow-character__img {
            max-height: 90vh;
            object-fit: contain;
        }

        @media (max-width: 768px) {
            .slideshow .slideshow-text h2 {
                font-size: 1.2rem;
                line-height: 1.3;
            }
            .slideshow .slideshow-text h6 { font-size: 0.8rem; }
            .slideshow .slideshow-text a { font-size: 0.8rem; padding: 6px 12px; }
            .slideshow .slideshow-character__img { max-height: 50vh; }
            
            /* Penyesuaian untuk slider "Mungkin Kamu Suka" di mobile */
            .category-coverflow-item {
                height: 240px; /* Mengurangi tinggi item di mobile */
            }
            .slide-content .title {
                font-size: 1.2rem; /* Mengurangi ukuran font judul di mobile */
            }
        }
    </style>


    <div class="container mw-1620 bg-white border-radius-10">
        <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>
        
        <section class="category-carousel container">
            <h2 class="section-title text-center mb-3 pb-xl-2 mb-xl-4">Mungkin Kamu Suka</h2>
            
            <div class="position-relative swiper-coverflow-wrapper">
                <div class="swiper-container js-swiper-slider category-coverflow"
                    data-settings='{
                        "effect": "coverflow",
                        "grabCursor": true,
                        "centeredSlides": true,
                        "slidesPerView": "auto",
                        "loop": true,
                        "autoplay": {
                            "delay": 3000,
                            "disableOnInteraction": false
                        },
                        "coverflowEffect": {
                            "rotate": 0,
                            "stretch": 0,
                            "depth": 100,
                            "modifier": 1,
                            "slideShadows": false
                        },
                        "navigation": {
                            "nextEl": ".products-carousel__next-1",
                            "prevEl": ".products-carousel__prev-1"
                        },
                        "breakpoints": {
                            "768": {
                                "slidesPerView": 2
                            },
                            "992": {
                                "slidesPerView": 3,
                                "coverflowEffect": {
                                    "stretch": 0
                                }
                            }
                        }
                    }'>
                    <div class="swiper-wrapper">
                        @foreach ($categories as $category)
                            <div class="swiper-slide">
                                <a href="{{ route('shop.index', ['categories' => $category->id]) }}" class="category-coverflow-item">
                                    <img loading="lazy" src="{{ asset('uploads/categories') }}/{{ $category->image }}" alt="{{ $category->name }}" />
                                    <div class="img-overlay"></div>
                                    <div class="slide-content">
                                        <!-- <p class="tagline">New Arrival</p>
                                        <h5 class="title">{{ $category->name }}</h5> -->
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="products-carousel__prev products-carousel__prev-1 position-absolute top-50 d-flex align-items-center justify-content-center">
                    <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><use href="#icon_prev_md" /></svg>
                </div>
                <div class="products-carousel__next products-carousel__next-1 position-absolute top-50 d-flex align-items-center justify-content-center">
                    <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><use href="#icon_next_md" /></svg>
                </div>
            </div>
        </section>

        <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

        <section class="hot-deals container">
            <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4">Penawaran Terbaik</h2>
            <div class="row">
                <div
                    class="col-md-6 col-lg-4 col-xl-20per d-flex align-items-center flex-column justify-content-center py-4 align-items-md-start">
                    <h2>Summer Sale</h2>
                    <h2 class="fw-bold">Up to {{ $maxDiscount ?? 0 }}% Off</h2>

                    <div class="position-relative d-flex align-items-center text-center pt-xxl-4 js-countdown mb-3"
                        data-date="18-3-2024" data-time="06:50">
                        <div class="day countdown-unit">
                            <span class="countdown-num d-block"></span>
                            <span class="countdown-word text-uppercase text-secondary">Days</span>
                        </div>
                        <div class="hour countdown-unit">
                            <span class="countdown-num d-block"></span>
                            <span class="countdown-word text-uppercase text-secondary">Hours</span>
                        </div>
                        <div class="min countdown-unit">
                            <span class="countdown-num d-block"></span>
                            <span class="countdown-word text-uppercase text-secondary">Mins</span>
                        </div>
                        <div class="sec countdown-unit">
                            <span class="countdown-num d-block"></span>
                            <span class="countdown-word text-uppercase text-secondary">Sec</span>
                        </div>
                    </div>

                    <a href="{{ route('shop.index') }}"
                        class="btn-link default-underline text-uppercase fw-medium mt-3">Lihat Semua</a>
                </div>
                <div class="col-md-6 col-lg-8 col-xl-80per">
                    <div class="position-relative">
                        <div class="swiper-container js-swiper-slider"
                            data-settings='{
                                "autoplay": { "delay": 5000 },
                                "slidesPerView": 4, "slidesPerGroup": 4, "effect": "none", "loop": false,
                                "breakpoints": {
                                    "320": { "slidesPerView": 2, "slidesPerGroup": 2, "spaceBetween": 14 },
                                    "768": { "slidesPerView": 2, "slidesPerGroup": 3, "spaceBetween": 24 },
                                    "992": { "slidesPerView": 3, "slidesPerGroup": 1, "spaceBetween": 30, "pagination": false },
                                    "1200": { "slidesPerView": 4, "slidesPerGroup": 1, "spaceBetween": 30, "pagination": false }
                                }
                            }'>
                            <div class="swiper-wrapper">
                                @foreach ($sproducts as $sproduct)
                                    <div class="swiper-slide product-card product-card_style3">
                                        <div class="pc__img-wrapper">
                                            <a href="{{ route('shop.product.details', ['product_slug' => $sproduct->slug]) }}">
                                                <img loading="lazy" src="{{ asset('uploads/products') }}/{{ $sproduct->image }}"
                                                    width="258" height="313" alt="{{ $sproduct->name }}" class="pc__img">
                                            </a>
                                        </div>
                                        <div class="pc__info position-relative">
                                            <h6 class="pc__title"><a href="{{ route('shop.product.details', ['product_slug' => $sproduct->slug]) }}">{{ $sproduct->name }}</a></h6>
                                            <div class="product-card__price d-flex align-items-center">
                                                @if ($sproduct->sale_price)
                                                    <span class="price-new">Rp {{ number_format($sproduct->sale_price, 0, ',', '.') }}</span>
                                                    <span class="price-old">Rp {{ number_format($sproduct->regular_price, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="price-new">Rp {{ number_format($sproduct->regular_price, 0, ',', '.') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

        <section class="category-banner container">
            <div class="row">
                @foreach ($bannerRandomCategories as $category)
                    @php
                        $lowestSaleProduct = $category->products->whereNotNull('sale_price')->sortBy('sale_price')->first();
                        $productImage = $category->products->first()?->image;
                    @endphp
                    <div class="col-md-6">
                        <div class="category-banner__item border-radius-10 mb-5">
                            @php
                                $isPng = $productImage && strtolower(pathinfo($productImage, PATHINFO_EXTENSION)) === 'png';
                            @endphp
                            <img loading="lazy" class="h-auto"
                                src="{{ $productImage ? asset('uploads/products/' . $productImage) : asset('assets/images/placeholder.jpg') }}"
                                width="690" height="665" alt="{{ $category->name }}" @style(['background-color: #f2f2f2' => $isPng]) />
                            <div class="category-banner__item-mark">
                                @if ($lowestSaleProduct)
                                    Mulai Dari Rp. {{ number_format($lowestSaleProduct->sale_price, 0, ',', '.') }}
                                @else
                                    Check the deals
                                @endif
                            </div>
                            <div class="category-banner__item-content">
                                <h3 class="mb-0">{{ $category->name }}</h3>
                                <a href="{{ url('/shop?categories=' . $category->id) }}"
                                    class="btn-link default-underline text-uppercase fw-medium">Belanja Sekarang</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

        <section class="products-grid container">
            <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4">Produk Unggulan</h2>
            <div class="row">
                @foreach ($fproducts as $fproduct)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
                            <div class="pc__img-wrapper">
                                <a href="{{ route('shop.product.details', ['product_slug' => $fproduct->slug]) }}">
                                    <img loading="lazy" src="{{ asset('uploads/products') }}/{{ $fproduct->image }}"
                                        width="330" height="400" alt="{{ $fproduct->name }}" class="pc__img">
                                </a>
                            </div>
                            <div class="pc__info position-relative">
                                <h6 class="pc__title"><a href="details.html">{{ $fproduct->name }}</a></h6>
                                <div class="product-card__price d-flex align-items-center">
                                    @if ($fproduct->sale_price)
                                        <span class="price-new">Rp {{ number_format($fproduct->sale_price, 0, ',', '.') }}</span>
                                        <span class="price-old">Rp {{ number_format($fproduct->regular_price, 0, ',', '.') }}</span>
                                    @else
                                        <span class="price-new">Rp {{ number_format($fproduct->regular_price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

    </div> 
</main>
@endsection