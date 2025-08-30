@extends('layouts.app')
@section('content')
    <style>
        .filled-heart {
            color: orange;
        }
        .product-card {
            border: none;
            background-color: transparent;
            text-align: left;
        }
        .pc__img-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
        }
        .pc__badge {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background-color: #d9534f;
            color: white;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 4px;
            z-index: 2;
        }
        .pc__info {
            padding-top: 12px;
        }
        .pc__title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 4px;
            line-height: 1.3;
        }
        .pc__title a {
            text-decoration: none;
            color: #222;
        }
        .pc__title a:hover {
            text-decoration: underline;
        }
        .pc__category {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .product-card__price .price {
            font-size: 1rem;
            font-weight: 700;
            color: #d9534f;
        }
        .product-card__price .price-old {
            font-size: 0.9rem;
            font-weight: 400;
            color: #6c757d;
            margin-left: 8px;
        }
        .pc__btn-wl {
            position: static;
            padding-left: 10px;
        }
    </style>
    <div class="mb-5"></div> <section class="shop-main container d-flex">
            {{-- SIDEBAR FILTER --}}
            <div class="shop-sidebar side-sticky bg-body" id="shopFilter">
                <div class="aside-header d-flex d-lg-none align-items-center">
                    <h3 class="text-uppercase fs-6 mb-0">Filter By</h3>
                    <button class="btn-close-lg js-close-aside btn-close-aside ms-auto"></button>
                </div>

                <div class="pt-4 pt-lg-0"></div>

                {{-- KATEGORI FILTER --}}
                <div class="accordion" id="categories-list">
                    <div class="accordion-item mb-5 pb-3">
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
                                                    @if (in_array($category->id, explode(',', $f_categories))) checked @endif />
                                                {{ $category->name }}
                                            </span>
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
                                                    @if (in_array($brand->id, explode(',', $f_brands))) checked @endif />
                                                {{ $brand->name }}
                                            </span>
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
                            <div class="d-flex align-items-center gap-2">
                                <input type="number" id="minPriceInput" class="form-control" placeholder="Harga Min..." value="{{ $min_price }}">
                                <span class="minus">-</span>
                                <input type="number" id="maxPriceInput" class="form-control" placeholder="Harga Max..." value="{{ $max_price }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="shop-list flex-grow-1">
                <div class="text-center mb-4 pb-md-2">
                    <h2 class="text-uppercase fw-bold">Produk</h2>
                </div>

                <div class="products-grid row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-3" id="products-grid">
                    @forelse ($products as $product)
                        <div class="product-card-wrapper">
                            <div class="product-card mb-3 mb-md-4 mb-xxl-5">
                                <div class="pc__img-wrapper">
                                    <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}">
                                        <img loading="lazy"
                                            src="{{ asset('uploads/products') }}/{{ $product->image }}"
                                            width="660" height="800" alt="{{ $product->name }}"
                                            class="pc__img img-fluid">
                                    </a>
                                    @if ($product->sale_price > 0 && $product->regular_price > 0)
                                        @php
                                            $discount = round((($product->regular_price - $product->sale_price) / $product->regular_price) * 100);
                                        @endphp
                                        <div class="pc__badge">{{ $discount }}% OFF</div>
                                    @endif
                                </div>

                                <div class="pc__info d-flex justify-content-between align-items-start">
                                    <div class="pc__info-content">
                                        <h6 class="pc__title">
                                            <a href="{{ route('shop.product.details', ['product_slug' => $product->slug]) }}">{{ $product->name }}</a>
                                        </h6>
                                        <p class="pc__category">{{ $product->category->name }}</p>
                                        {{-- LOGIKA BARU: Tampilkan harga langsung tanpa dikali 1000 --}}
                                        <div class="product-card__price">
                                            @if ($product->sale_price > 0)
                                                <span class="money price">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                                <del class="money price-old">Rp {{ number_format($product->regular_price, 0, ',', '.') }}</del>
                                            @else
                                                <span class="money price text-dark">Rp {{ number_format($product->regular_price, 0, ',', '.') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="pc__actions">
                                        @php
                                            $inWishlist = auth()->check() && \App\Models\Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->exists();
                                        @endphp
                                        @if ($inWishlist)
                                            <form action="{{ route('wishlist.item.remove', ['product_id' => $product->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="pc__btn-wl bg-transparent border-0 js-add-wishlist filled-heart" title="Remove from Wishlist">
                                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_heart" /></svg>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('wishlist.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $product->id }}">
                                                <button type="submit" class="pc__btn-wl bg-transparent border-0 js-add-wishlist" title="Add To Wishlist">
                                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_heart" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
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
        document.addEventListener("DOMContentLoaded", function() {
            function submitFilterForm() {
                document.getElementById('frmFilter').submit();
            }

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

            // LOGIKA BARU: FILTER HANYA BERJALAN DENGAN TOMBOL ENTER
            const minPriceInput = document.getElementById('minPriceInput');
            const maxPriceInput = document.getElementById('maxPriceInput');

            function applyPriceFilterIfReady() {
                const minPriceValue = minPriceInput.value;
                const maxPriceValue = maxPriceInput.value;

                // Hanya jalankan jika kedua kolom memiliki isi
                if (minPriceValue && maxPriceValue) {
                    document.getElementById('hdnMinPrice').value = minPriceValue;
                    document.getElementById('hdnMaxPrice').value = maxPriceValue;
                    submitFilterForm();
                }
            }
            
            function handleEnterKey(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Mencegah perilaku default form
                    applyPriceFilterIfReady();
                }
            }

            if (minPriceInput && maxPriceInput) {
                // HAPUS event listener 'input' untuk mencegah refresh otomatis
                // minPriceInput.addEventListener('input', applyPriceFilterIfReady);
                // maxPriceInput.addEventListener('input', applyPriceFilterIfReady);

                // HANYA gunakan event listener 'keydown' untuk tombol Enter
                minPriceInput.addEventListener('keydown', handleEnterKey);
                maxPriceInput.addEventListener('keydown', handleEnterKey);
            }
        });
    </script>
@endpush