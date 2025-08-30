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

                    <!-- Kolom Gambar -->
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

                    <!-- Kolom Teks -->
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
/* Default desktop */
.slideshow .slideshow-character__img {
  max-height: 90vh;
  object-fit: contain;
}

/* Mobile khusus */
@media (max-width: 768px) {
  .slideshow .slideshow-text h2 {
    font-size: 1.2rem; /* kecilkan judul */
    line-height: 1.3;
  }
  .slideshow .slideshow-text h6 {
    font-size: 0.8rem;
  }
  .slideshow .slideshow-text a {
    font-size: 0.8rem;
    padding: 6px 12px;
  }
  .slideshow .slideshow-character__img {
    max-height: 50vh;
  }
}
</style>



    <div class="container mw-1620 bg-white border-radius-10">
        <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>


        <section class="container">
            <h2 class="section-title text-center mb-3 pb-xl-2 mb-xl-4">Mungkin Kamu Suka</h2>
            <div class="swiper-container js-swiper-slider"
                data-settings='{
            "autoplay": {
                "delay": 5000
            },
            "slidesPerView": 1,
            "effect": "fade",
            "loop": true,
            "pagination": {
                "el": ".swiper-pagination-mks",
                "clickable": true
            }
        }'>
                <div class="swiper-wrapper">
                    @foreach ($categories as $category)
                    <div class="swiper-slide">
                        <div class="position-relative">
                            {{-- Background Image --}}
                            <img loading="lazy" src="{{ asset('uploads/categories') }}/{{ $category->image }}"
                                alt="{{ $category->name }}" class="w-100" style="height: 350px; object-fit: cover; border-radius: 10px;" />
                            {{-- Empty Overlay --}}
                            <div class="position-absolute start-0 top-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background-color: rgba(0, 0, 0, 0.35); border-radius: 10px;">
                                {{-- Konten dihilangkan --}}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                {{-- Pagination --}}
                <div class="swiper-pagination swiper-pagination-mks"></div>
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
                  "autoplay": {
                    "delay": 5000
                  },
                  "slidesPerView": 4,
                  "slidesPerGroup": 4,
                  "effect": "none",
                  "loop": false,
                  "breakpoints": {
                    "320": {
                      "slidesPerView": 2,
                      "slidesPerGroup": 2,
                      "spaceBetween": 14
                    },
                    "768": {
                      "slidesPerView": 2,
                      "slidesPerGroup": 3,
                      "spaceBetween": 24
                    },
                    "992": {
                      "slidesPerView": 3,
                      "slidesPerGroup": 1,
                      "spaceBetween": 30,
                      "pagination": false
                    },
                    "1200": {
                      "slidesPerView": 4,
                      "slidesPerGroup": 1,
                      "spaceBetween": 30,
                      "pagination": false
                    }
                  }
                }'>
                            <div class="swiper-wrapper">
                                @foreach ($sproducts as $sproduct)
                                <div class="swiper-slide product-card product-card_style3">
                                    <div class="pc__img-wrapper">
                                        <a
                                            href="{{ route('shop.product.details', ['product_slug' => $sproduct->slug]) }}">
                                            <img loading="lazy"
                                                src="{{ asset('uploads/products') }}/{{ $sproduct->image }}"
                                                width="258" height="313" alt="{{ $sproduct->name }}"
                                                class="pc__img">
                                        </a>
                                    </div>

                                    <div class="pc__info position-relative">
                                        <h6 class="pc__title"><a
                                                href="{{ route('shop.product.details', ['product_slug' => $sproduct->slug]) }}">{{ $sproduct->name }}</a>
                                        </h6>
                                        <div class="product-card__price d-flex">
                                            <span class="money price text-secondary">
                                                @if ($sproduct->sale_price)
                                                <s>Rp. {{ $sproduct->regular_price }}</s>
                                                Rp. {{ $sproduct->sale_price }}
                                                @else
                                                Rp. {{ $sproduct->regular_price }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>@endforeach
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
                $lowestSaleProduct = $category->products
                ->whereNotNull('sale_price')
                ->sortBy('sale_price')
                ->first();
                $productImage = $category->products->first()?->image;
                @endphp
                <div class="col-md-6">
                    <div class="category-banner__item border-radius-10 mb-5">
                        @php
                        $imagePath = $productImage
                        ? public_path('uploads/products/' . $productImage)
                        : null;
                        $isPng =
                        $productImage &&
                        strtolower(pathinfo($productImage, PATHINFO_EXTENSION)) === 'png';
                        @endphp

                        <img loading="lazy" class="h-auto"
                            src="{{ $productImage ? asset('uploads/products/' . $productImage) : asset('assets/images/placeholder.jpg') }}"
                            width="690" height="665" alt="{{ $category->name }}"
                            @style(['background-color: #f2f2f2'=> $isPng]) />


                        <div class="category-banner__item-mark">
                            @if ($lowestSaleProduct)
                            Mulai Dari Rp. {{ number_format($lowestSaleProduct->sale_price, 2) }}
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
                                <span class="money price text-secondary">
                                    @if ($fproduct->sale_price)
                                    <s>Rp. {{ $fproduct->regular_price }}</s>
                                    Rp. {{ $fproduct->sale_price }}
                                    @else
                                    Rp. {{ $fproduct->regular_price }}
                                    @endif
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach
            </div>{{-- <div class="text-center mt-2">
                    <a class="btn-link btn-link_lg default-underline text-uppercase fw-medium" href="#">Load
                        More</a>
                </div> --}}
        </section>
    </div>

    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

</main>
@endsection

{{-- Mungkin Kamu Suka --}}
@if(isset($relatedProducts) && $relatedProducts->count() > 0)
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold mb-6">Mungkin Kamu Suka</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($relatedProducts as $product)
            <div class="border rounded-lg shadow hover:shadow-lg transition p-4 bg-white">
                <a href="{{ route('products.show', $product->slug) }}">
                    {{-- Gambar produk (tidak terpotong) --}}
                    <div class="w-full h-64 bg-white flex items-center justify-center">
                        <img src="{{ asset('storage/' . $product->image) }}"
                            alt="{{ $product->name }}"
                            class="max-h-full max-w-full object-contain">
                    </div>

                    {{-- Nama Produk --}}
                    <h3 class="mt-3 text-base font-semibold truncate">
                        {{ $product->name }}
                    </h3>

                    {{-- Harga Produk --}}
                    <p class="text-gray-600 mt-1">
                        Rp{{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif