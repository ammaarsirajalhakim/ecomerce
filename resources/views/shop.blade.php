@extends('layouts.app')
@section('content')
    <style>
        .filled-heart {
            color: orange;
        }
    </style>
    <main class="pt-90">
        <section class="shop-main container d-flex pt-4 pt-xl-5">
            <div class="shop-sidebar side-sticky bg-body" id="shopFilter">
                <div class="aside-header d-flex d-lg-none align-items-center">
                    <h3 class="text-uppercase fs-6 mb-0">Filter By</h3>
                    <button class="btn-close-lg js-close-aside btn-close-aside ms-auto"></button>
                </div>

                <div class="pt-4 pt-lg-0"></div>

                {{-- KATEGORI FILTER --}}
                <div class="accordion" id="categories-list">
                    <div class="accordion-item mb-4 pb-3">
                        <h5 class="accordion-header" id="accordion-heading-1">
                            <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button"
                                data-bs-toggle="collapse" data-bs-target="#accordion-filter-1" aria-expanded="true"
                                aria-controls="accordion-filter-1">
                                Kategori
                                <svg class="accordion-button__icon type2" viewBox="0 0 10 6"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                                        <path
                                            d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                                    </g>
                                </svg>
                            </button>
                        </h5>
                        <div id="accordion-filter-1" class="accordion-collapse collapse show border-0"
                            aria-labelledby="accordion-heading-1" data-bs-parent="#categories-list">
                            <div class="accordion-body px-0 pb-0 pt-3">
                                <ul class="list list-inline mb-0">
                                    @foreach ($categories as $category)
                                        <li class="list-item">
                                            <span class="menu-link py-1">
                                                <input type="checkbox" name="categories" class="chk-category"
                                                    value="{{ $category->id }}"
                                                    @if (in_array($category->id, explode(',', $f_categories))) checked="checked" @endif />
                                                {{ $category->name }}
                                            </span>
                                            {{-- PERBAIKAN: Gunakan 'products_count' dari Controller --}}
                                            <span class="text-right float-end">{{ $category->products_count }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BRAND FILTER --}}
                <div class="accordion" id="brand-filters">
                    <div class="accordion-item mb-4 pb-3">
                        <h5 class="accordion-header" id="accordion-heading-brand">
                            <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button"
                                data-bs-toggle="collapse" data-bs-target="#accordion-filter-brand"
                                aria-expanded="true" aria-controls="accordion-filter-brand">
                                Brand
                                <svg class="accordion-button__icon type2" viewBox="0 0 10 6"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                                        <path
                                            d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                                    </g>
                                </svg>
                            </button>
                        </h5>
                        <div id="accordion-filter-brand" class="accordion-collapse collapse show border-0"
                            aria-labelledby="accordion-heading-brand" data-bs-parent="#brand-filters">
                            <div class="search-field multi-select accordion-body px-0 pb-0">
                                <ul class="list list-inline mb-0 brand-list">
                                    @foreach ($brands as $brand)
                                        <li class="list-item">
                                            <span class="menu-link py-1">
                                                <input type="checkbox" name="brands" class="chk-brand"
                                                    value="{{ $brand->id }}"
                                                    @if (in_array($brand->id, explode(',', $f_brands))) checked="checked" @endif />
                                                {{ $brand->name }}
                                            </span>
                                            {{-- PERBAIKAN: Gunakan 'products_count' dari Controller --}}
                                            <span class="float-end">{{ $brand->products_count }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- HARGA FILTER --}}
                <div class="accordion" id="price-filters">
                    <div class="accordion-item mb-4">
                        <h5 class="accordion-header mb-2" id="accordion-heading-price">
                            <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button"
                                data-bs-toggle="collapse" data-bs-target="#accordion-filter-price"
                                aria-expanded="true" aria-controls="accordion-filter-price">
                                Harga
                                <svg class="accordion-button__icon type2" viewBox="0 0 10 6"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                                        <path
                                            d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z" />
                                    </g>
                                </svg>
                            </button>
                        </h5>
                        <div id="accordion-filter-price" class="accordion-collapse collapse show border-0"
                            aria-labelledby="accordion-heading-price" data-bs-parent="#price-filters">
                            <input class="price-range-slider" type="text" name="price_range" value=""
                                data-slider-min="1" data-slider-max="500" data-slider-step="5"
                                data-slider-value="[{{ $min_price }},{{ $max_price }}]" data-currency="Rp" />
                            <div class="price-range__info d-flex align-items-center mt-2">
                                <div class="me-auto">
                                    <span class="text-secondary">Min: </span>
                                    <span class="price-range__min">Rp. 1</span>
                                </div>
                                <div>
                                    <span class="text-secondary">Max: </span>
                                    <span class="price-range__max">Rp 500.000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="shop-list flex-grow-1">
                <div class="d-flex justify-content-between mb-4 pb-md-2">
                    <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
                        <a href="{{ route('home.index') }}"
                            class="menu-link menu-link_us-s text-uppercase fw-medium">Home</a>
                        <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
                        <a href="{{ route('shop.index') }}" class="menu-link menu-link_us-s text-uppercase fw-medium">Shop</a>
                    </div>

                    <div
                        class="shop-acs d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
                        <select class="shop-acs__select form-select w-auto border-0 py-0 order-1 order-md-0"
                            style="margin-right: 20px" id="pagesize">
                            <option value="12" @if($size == 12) selected @endif>Show 12</option>
                            <option value="24" @if($size == 24) selected @endif>Show 24</option>
                            <option value="36" @if($size == 36) selected @endif>Show 36</option>
                        </select>
                        <select class="shop-acs__select form-select w-auto border-0 py-0 order-1 order-md-0"
                             id="orderby">
                            <option value="-1" @if($order == -1) selected @endif>Default</option>
                            <option value="1" @if($order == 1) selected @endif>Tanggal, Baru ke Lama</option>
                            <option value="2" @if($order == 2) selected @endif>Tanggal, Lama ke Baru</option>
                            <option value="3" @if($order == 3) selected @endif>Harga, Tinggi ke Rendah</option>
                            <option value="4" @if($order == 4) selected @endif>Harga, Rendah ke Tinggi</option>
                        </select>
                    </div>
                </div>

                {{-- DAFTAR PRODUK --}}
                <div class="products-grid row row-cols-2 row-cols-md-3" id="products-grid">
                    @forelse ($products as $product)
                        <div class="product-card-wrapper">
                            <div class="product-card mb-3 mb-md-4 mb-xxl-5">
                                <div class="pc__img-wrapper">
                                    <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}">
                                        <img loading="lazy"
                                            src="{{ asset('uploads/products') }}/{{ $product->image }}"
                                            width="330" height="400" alt="{{ $product->name }}"
                                            class="pc__img">
                                        {{-- Jika ada gambar kedua, bisa ditampilkan di sini atau dengan swiper --}}
                                    </a>
                                    @php
                                        $inCart = auth()->check() && \App\Models\CartItem::where('user_id', auth()->id())->where('product_id', $product->id)->exists();
                                    @endphp
                                    @if ($inCart)
                                        <a href="{{ route('cart.index') }}"
                                            class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium btn-warning">Lihat Keranjang</a>
                                    @else
                                        <form action="{{ route('cart.add') }}" method="POST" name="addtocart-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product->id }}" />
                                            <input type="hidden" name="quantity" value="1" />
                                            <input type="hidden" name="name" value="{{ $product->name }}" />
                                            {{-- PERBAIKAN: Logika harga untuk form --}}
                                            <input type="hidden" name="price" value="{{ $product->sale_price > 0 ? $product->sale_price : $product->regular_price }}" />
                                            <button
                                                class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium"
                                                type="submit" title="Add To Cart">Masukkan Keranjang</button>
                                        </form>
                                    @endif
                                </div>

                                <div class="pc__info position-relative">
                                    <p class="pc__category">{{ $product->category->name }}</p>
                                    <h6 class="pc__title"><a
                                            href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}">{{ $product->name }}</a>
                                    </h6>
                                    <div class="product-card__price d-flex">
                                        <span class="money price">
                                            {{-- PERBAIKAN: Logika Tampilan Harga --}}
                                            @if ($product->sale_price > 0)
                                                <del>Rp. {{ number_format($product->regular_price) }}</del>
                                                <span class="text-danger">Rp. {{ number_format($product->sale_price) }}</span>
                                            @else
                                                Rp. {{ number_format($product->regular_price) }}
                                            @endif
                                        </span>
                                    </div>

                                    @php
                                        $inWishlist = auth()->check() && \App\Models\Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->exists();
                                    @endphp

                                    @if ($inWishlist)
                                        <form action="{{ route('wishlist.item.remove', ['product_id' => $product->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist filled-heart"
                                                title="Remove from Wishlist">
                                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <use href="#icon_heart" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('wishlist.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product->id }}">
                                            <button type="submit"
                                                class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist"
                                                title="Add To Wishlist">
                                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <use href="#icon_heart" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        {{-- PERBAIKAN: Pesan jika tidak ada produk --}}
                        <div class="col-12 text-center">
                            <p>Mohon maaf, tidak ada produk yang ditemukan.</p>
                        </div>
                    @endforelse
                </div>

                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wg-pagination">
                    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </section>
    </main>

    {{-- Form tersembunyi untuk filter --}}
    <form action="{{ route('shop.index') }}" method="GET" id="frmFilter">
        <input type="hidden" name="size" id="size" value="{{ $size }}">
        <input type="hidden" name="order" id="order" value="{{ $order }}">
        <input type="hidden" name="brands" id="hdnBrands" value="{{ $f_brands }}">
        <input type="hidden" name="categories" id="hdnCategories" value="{{ $f_categories }}">
        <input type="hidden" name="min" id="hdnMinPrice" value="{{ $min_price }}">
        <input type="hidden" name="max" id="hdnMaxPrice" value="{{ $max_price }}">
    </form>
@endsection

@push('scripts')
    <script>
        // Gunakan event listener modern dan pastikan elemen ada sebelum menambahkan listener
        document.addEventListener("DOMContentLoaded", function() {
            // Fungsi untuk mengirim form filter
            function submitFilterForm() {
                document.getElementById('frmFilter').submit();
            }

            // Listener untuk ukuran halaman
            const pagesizeSelect = document.getElementById('pagesize');
            if (pagesizeSelect) {
                pagesizeSelect.addEventListener('change', function() {
                    document.getElementById('size').value = this.value;
                    submitFilterForm();
                });
            }

            // Listener untuk urutan
            const orderbySelect = document.getElementById('orderby');
            if (orderbySelect) {
                orderbySelect.addEventListener('change', function() {
                    document.getElementById('order').value = this.value;
                    submitFilterForm();
                });
            }
            
            // Listener untuk checkbox brand
            const brandCheckboxes = document.querySelectorAll("input[name='brands']");
            brandCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    let selectedBrands = Array.from(document.querySelectorAll("input[name='brands']:checked"))
                                              .map(cb => cb.value)
                                              .join(',');
                    document.getElementById('hdnBrands').value = selectedBrands;
                    submitFilterForm();
                });
            });

            // Listener untuk checkbox kategori
            const categoryCheckboxes = document.querySelectorAll("input[name='categories']");
            categoryCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    let selectedCategories = Array.from(document.querySelectorAll("input[name='categories']:checked"))
                                                  .map(cb => cb.value)
                                                  .join(',');
                    document.getElementById('hdnCategories').value = selectedCategories;
                    submitFilterForm();
                });
            });

            // Untuk price range slider (jika menggunakan library seperti ion-rangeslider atau sejenisnya)
            // Kode ini hanya contoh, sesuaikan dengan library yang Anda pakai
            const priceSlider = document.querySelector(".price-range-slider");
            if (priceSlider) {
                // Asumsikan library slider memicu event 'change'
                priceSlider.addEventListener('change', function() {
                    const values = this.value.split(',');
                    document.getElementById('hdnMinPrice').value = values[0];
                    document.getElementById('hdnMaxPrice').value = values[1];
                    // Tambahkan delay agar tidak mengirim form terus-menerus saat slider digeser
                    setTimeout(submitFilterForm, 1500);
                });
            }
        });
    </script>
@endpush