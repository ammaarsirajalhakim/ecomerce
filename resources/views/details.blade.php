@extends('layouts.app')
@section('content')
    <style>
        .filled-heart {
            color: orange;
        }
    </style>
    <main class="pt-90">
        <div class="mb-md-1 pb-md-3"></div>
        <section class="product-single container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="product-single__media" data-media-type="vertical-thumbnail">
                        <div class="product-single__image">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    {{-- Gambar Utama --}}
                                    <div class="swiper-slide product-single__image-item">
                                        <img loading="lazy" class="h-auto"
                                            src="{{ asset('uploads/products') }}/{{ $product->image }}" width="674"
                                            height="674" alt="{{ $product->name }}" />
                                        <a data-fancybox="gallery" href="{{ asset('uploads/products') }}/{{ $product->image }}" data-bs-toggle="tooltip" data-bs-placement="left" title="Zoom">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <use href="#icon_zoom" />
                                            </svg>
                                        </a>
                                    </div>

                                    {{-- PERBAIKAN: Looping Galeri Gambar yang Aman --}}
                                    @if($product->images)
                                        @foreach (explode(',', $product->images) as $gimg)
                                            @if (trim($gimg) != '')
                                                <div class="swiper-slide product-single__image-item">
                                                    <img loading="lazy" class="h-auto"
                                                        src="{{ asset('uploads/products') }}/{{ trim($gimg) }}" width="674"
                                                        height="674" alt="{{ $product->name }} gallery image" />
                                                    <a data-fancybox="gallery" href="{{ asset('uploads/products') }}/{{ trim($gimg) }}" data-bs-toggle="tooltip" data-bs-placement="left" title="Zoom">
                                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <use href="#icon_zoom" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                                <div class="swiper-button-prev"><svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_prev_sm" /></svg></div>
                                <div class="swiper-button-next"><svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg"><use href="#icon_next_sm" /></svg></div>
                            </div>
                        </div>
                        <div class="product-single__thumbnail">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide product-single__image-item">
                                        <img loading="lazy" class="h-auto" src="{{ asset('uploads/products') }}/{{ $product->image }}" width="104" height="104" alt="{{ $product->name }}" />
                                    </div>
                                    @if($product->images)
                                        @foreach (explode(',', $product->images) as $gimg)
                                             @if (trim($gimg) != '')
                                                <div class="swiper-slide product-single__image-item">
                                                    <img loading="lazy" class="h-auto" src="{{ asset('uploads/products') }}/{{ trim($gimg) }}" width="104" height="104" alt="{{ $product->name }} gallery thumbnail" />
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="d-flex justify-content-between mb-4 pb-md-2">
                        <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
                            <a href="{{ route('home.index') }}" class="menu-link menu-link_us-s text-uppercase fw-medium">Home</a>
                            <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
                            <a href="{{ route('shop.index') }}" class="menu-link menu-link_us-s text-uppercase fw-medium">Shop</a>
                        </div>
                        <div class="product-single__prev-next d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
                            {{-- PERBAIKAN: Tombol Prev/Next dari Controller --}}
                            @if ($prev_product)
                                <a href="{{ route('shop.product.details', ['product_slug' => $prev_product->slug]) }}" class="text-uppercase fw-medium">
                                    <svg width="10" height="10" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><use href="#icon_prev_md" /></svg>
                                    <span class="menu-link menu-link_us-s">Prev</span>
                                </a>
                            @endif
                             @if ($next_product)
                                <a href="{{ route('shop.product.details', ['product_slug' => $next_product->slug]) }}" class="text-uppercase fw-medium ms-4">
                                    <span class="menu-link menu-link_us-s">Next</span>
                                    <svg width="10" height="10" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><use href="#icon_next_md" /></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                    <h1 class="product-single__name">{{ $product->name }}</h1>
                    <div class="product-single__price">
                        {{-- PERBAIKAN: Logika Tampilan Harga --}}
                        @if ($product->sale_price > 0)
                            <span class="current-price text-danger">Rp. {{ number_format($product->sale_price) }}</span>
                            <span class="old-price text-muted"><del>Rp. {{ number_format($product->regular_price) }}</del></span>
                        @else
                            <span class="current-price">Rp. {{ number_format($product->regular_price) }}</span>
                        @endif
                    </div>
                    <div class="product-single__short-desc">
                        <p>{!! $product->short_description !!}</p>
                    </div>
                    
                    @php
                        $inCart = auth()->check() && \App\Models\CartItem::where('user_id', auth()->id())->where('product_id', $product->id)->exists();
                    @endphp
                    @if ($inCart)
                        <a href="{{ route('cart.index') }}" class="btn btn-warning mb-3 w-100">Lihat Keranjang</a>
                    @else
                        <form name="addtocart-form" method="post" action="{{ route('cart.add') }}">
                            @csrf
                            <div class="product-single__addtocart">
                                <div class="qty-control position-relative">
                                    <input type="number" name="quantity" value="1" min="1" class="qty-control__number text-center">
                                    <div class="qty-control__reduce">-</div>
                                    <div class="qty-control__increase">+</div>
                                </div>
                                <input type="hidden" name="id" value="{{ $product->id }}" />
                                {{-- PERBAIKAN: Logika harga untuk form --}}
                                <input type="hidden" name="price" value="{{ $product->sale_price > 0 ? $product->sale_price : $product->regular_price }}" />
                                <button type="submit" class="btn btn-primary btn-addtocart" data-aside="cartDrawer">Masukkan Keranjang</button>
                            </div>
                        </form>
                    @endif

                    <div class="product-single__meta-info">
                        <div class="meta-item">
                            <label>SKU:</label>
                            <span>{{ $product->SKU }}</span>
                        </div>
                        <div class="meta-item">
                            <label>Kategori:</label>
                            <span>{{ $product->category->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-single__details-tab">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link nav-link_underscore active" id="tab-description-tab" data-bs-toggle="tab" href="#tab-description" role="tab" aria-controls="tab-description" aria-selected="true">Deskripsi</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link nav-link_underscore" id="tab-reviews-tab" data-bs-toggle="tab" href="#tab-reviews" role="tab" aria-controls="tab-reviews" aria-selected="false">Review</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-description" role="tabpanel" aria-labelledby="tab-description-tab">
                        <div class="product-single__description">
                           {{-- PERBAIKAN: Gunakan {!! !!} untuk render HTML dari deskripsi --}}
                           {!! $product->description !!}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-reviews" role="tabpanel" aria-labelledby="tab-reviews-tab">
                        {{-- Bagian review bisa diisi sesuai kebutuhan nanti --}}
                        <p>Belum ada review untuk produk ini.</p>
                    </div>
                </div>
            </div>
        </section>
        
        {{-- Produk Serupa --}}
        @if($related_products->count() > 0)
        <section class="products-carousel container">
            <h2 class="h3 text-uppercase mb-4 pb-xl-2 mb-xl-4"><strong>Produk </strong>Serupa</h2>
            <div id="related_products" class="position-relative">
                <div class="swiper-container js-swiper-slider" data-settings='{"autoplay": false, "slidesPerView": 4, "slidesPerGroup": 4, "loop": true, "pagination": {"el": "#related_products .products-pagination", "type": "bullets", "clickable": true}, "navigation": {"nextEl": "#related_products .products-carousel__next", "prevEl": "#related_products .products-carousel__prev"}, "breakpoints": {"320": {"slidesPerView": 2, "slidesPerGroup": 2, "spaceBetween": 14}, "768": {"slidesPerView": 3, "slidesPerGroup": 3, "spaceBetween": 24}, "992": {"slidesPerView": 4, "slidesPerGroup": 4, "spaceBetween": 30}}}'>
                    <div class="swiper-wrapper">
                        @foreach ($related_products as $rproduct)
                            <div class="swiper-slide product-card">
                                <div class="pc__img-wrapper">
                                    <a href="{{ route('shop.product.details', ['product_slug' => $rproduct->slug]) }}">
                                        <img loading="lazy" src="{{ asset('uploads/products') }}/{{ $rproduct->image }}" width="330" height="400" alt="{{ $rproduct->name }}" class="pc__img">
                                    </a>
                                </div>
                                <div class="pc__info position-relative">
                                    <h6 class="pc__title"><a href="{{ route('shop.product.details', ['product_slug' => $rproduct->slug]) }}">{{ $rproduct->name }}</a></h6>
                                    <div class="product-card__price d-flex">
                                        <span class="money price">
                                            @if ($rproduct->sale_price > 0)
                                                <del>Rp. {{ number_format($rproduct->regular_price) }}</del>
                                                <span class="text-danger">Rp. {{ number_format($rproduct->sale_price) }}</span>
                                            @else
                                                Rp. {{ number_format($rproduct->regular_price) }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="products-carousel__prev position-absolute top-50 d-flex align-items-center justify-content-center"><svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><use href="#icon_prev_md" /></svg></div>
                <div class="products-carousel__next position-absolute top-50 d-flex align-items-center justify-content-center"><svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><use href="#icon_next_md" /></svg></div>
                <div class="products-pagination mt-4 mb-5 d-flex align-items-center justify-content-center"></div>
            </div>
        </section>
        @endif
    </main>
@endsection