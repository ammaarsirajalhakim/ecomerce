@extends('layouts.app')
@section('content')
    <style>
        .text-success {
            color: #278c04 !important
        }

        .text-danger {
            color: #dc3545 !important
        }

        /* Tambahkan style untuk tombol yang dinonaktifkan */
        .btn-checkout.disabled {
            background-color: #ccc;
            border-color: #ccc;
            cursor: not-allowed;
        }
    </style>
    <main class="pt-20">
        
        <section class="shop-checkout container">
            <h2 class="page-title">Keranjang</h2>
            <div class="checkout-steps">
                <a href="javascript:void(0)" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">01</span>
                    <span class="checkout-steps__item-title">
                        <span>Tas Belanja</span>
                        <em>Kelola Daftar Item Anda</em>
                    </span>
                </a>
                <a href="javascript:void(0)" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">02</span>
                    <span class="checkout-steps__item-title">
                        <span>Pengiriman dan Checkout</span>
                        <em>Checkout Pesanan Anda</em>
                    </span>
                </a>
                <a href="javascript:void(0)" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">03</span>
                    <span class="checkout-steps__item-title">
                        <span>Konfirmasi</span>
                        <em>Lihat dan Konfirmasi Pesanan</em>
                    </span>
                </a>
            </div>
            <div class="shopping-cart">
                @if ($items->count() > 0)
                    <div class="cart-table__wrapper">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th></th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    {{-- Tambahkan atribut data untuk menyimpan sisa stok --}}
                                    <tr class="cart-item" data-stock="{{ $item->product->quantity }}">
                                        <td>
                                            <div class="shopping-cart__product-item">
                                                <a
                                                    href="{{ route('shop.product.details', ['product_slug' => $item->product->slug]) }}">
                                                    <img loading="lazy"
                                                        src="{{ asset('uploads/products/thumbnails') }}/{{ $item->product->image }}"
                                                        width="120" height="120" alt="{{ $item->product->name }}" />
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shopping-cart__product-item__detail">
                                                <a
                                                    href="{{ route('shop.product.details', ['product_slug' => $item->product->slug]) }}">
                                                    <h4>{{ $item->product->name }}</h4>
                                                </a>
                                                <ul class="shopping-cart__product-item__options">
                                                    <li>sisa stok: <span
                                                            class="stock-quantity">{{ $item->product->quantity }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="shopping-cart__product-price">Rp. {{ $item->price }}</span>
                                        </td>
                                        <td>
                                            <div class="qty-control position-relative">
                                                <form action="{{ route('cart.qty.update', ['id' => $item->id]) }}"
                                                    method="POST" class="qty-control position-relative">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}"
                                                        min="1" class="qty-control__number text-center"
                                                        onchange="this.form.submit()">
                                                </form>
                                                <form action="{{ route('cart.qty.decrease', ['id' => $item->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="qty-control__reduce">-</div>
                                                </form>
                                                <form action="{{ route('cart.qty.increase', ['id' => $item->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="qty-control__increase">+</div>
                                                </form>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="shopping-cart__subtotal">Rp. {{ $item->subtotal }}</span>
                                        </td>
                                        <td>
                                            <form action="{{ route('cart.item.remove', ['id' => $item->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <a href="javascript:void(0)" class="remove-cart">
                                                    <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                                                        <path
                                                            d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                                                    </svg>
                                                </a>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="cart-table-footer">
                            @if (!Session::has('coupon'))
                                <form action="{{ route('cart.coupon.apply') }}" method="POST"
                                    class="position-relative bg-body">
                                    @csrf
                                    <input class="form-control" type="text" name="coupon_code" placeholder="Kode Voucher"
                                        value="">
                                    <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4"
                                        type="submit" value="APPLY VOUCHER">
                                </form>
                            @else
                                <form action="{{ route('cart.coupon.remove') }}" method="POST"
                                    class="position-relative bg-body">
                                    @csrf
                                    @method('DELETE')
                                    <input class="form-control" type="text" name="coupon_code" placeholder="Coupon Code"
                                        value="@if (Session::has('coupon')) {{ Session::get('coupon')['code'] }} Applied! @endif">
                                    <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4"
                                        type="submit" value="HAPUS VOUCHER">
                                </form>
                            @endif
                            <form action="{{ route('cart.empty') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="sumbit" class="btn btn-light">BERSIHKAN KERANJANG</button>
                            </form>
                        </div>
                        <div>
                            @if (Session::has('success'))
                                <p class="text-success">
                                    {{ Session::get('success') }}
                                </p>
                            @elseif(Session::has('error'))
                                <p class="text-danger">
                                    {{ Session::get('error') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="shopping-cart__totals-wrapper">
                        <div class="sticky-content">
                            <div class="shopping-cart__totals">
                                <h3>Total Keranjang</h3>
                                @if (Session::has('discounts'))
                                    <table class="cart-totals">
                                        <tbody>
                                            <tr>
                                                <th>Subtotal</th>
                                                <td>Rp. {{ $subtotal }}</td>
                                            </tr>
                                            <tr>
                                                <th>Diskon {{ Session::get('coupon')['code'] }}</th>
                                                <td>Rp. {{ Session::get('discounts')['discount'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Subtotal Setelah Diskon</th>
                                                <td>Rp. {{ Session::get('discounts')['subtotal'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Pengiriman</th>
                                                <td>Free</td>
                                            </tr>
                                            <tr>
                                                <th>Pajak</th>
                                                <td>Rp. {{ Session::get('discounts')['tax'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td>Rp. {{ Session::get('discounts')['total'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <table class="cart-totals">
                                        <tbody>
                                            <tr>
                                                <th>Subtotal</th>
                                                <td>Rp. {{ $subtotal }}</td>
                                            </tr>
                                            <tr>
                                                <th>Pengiriman</th>
                                                <td>Free</td>
                                            </tr>
                                            <tr>
                                                <th>Pajak</th>
                                                <td>Rp. {{ $tax }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td>Rp. {{ $total }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <div class="mobile_fixed-btn_wrapper">
                                <div class="button-wrapper container">
                                    {{-- PERUBAHAN: Tambahkan ID dan href default --}}
                                    <a href="{{ route('cart.checkout') }}" id="checkout-btn"
                                        class="btn btn-primary btn-checkout">CHECKOUT</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-12 text-center pt-5 bp-5">
                            <p>Tidak ada item di keranjang</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-info">Belanja Sekarang</a>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        $(function() {
            function showErrorToast(message) {
                Toastify({
                    text: message,
                    duration: 3500,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        padding: "16px",
                        fontSize: "15px",
                        background: "white",
                        color: "#e74c3c", // Warna merah untuk error
                        border: "1px solid #e74c3c",
                        borderRadius: "8px"
                    }
                }).showToast();
            }

            // --- [LOGIKA BARU UNTUK VALIDASI STOK] ---
            function validateStock() {
                let isOutOfStock = false;

                // Iterasi setiap item di keranjang
                $('.cart-item').each(function() {
                    const stock = parseInt($(this).data('stock'));
                    if (stock <= 0) {
                        isOutOfStock = true;
                        // Tambahkan style visual pada produk yang stoknya habis (opsional)
                        $(this).css('opacity', '0.6');
                    }
                });

                const checkoutBtn = $('#checkout-btn');

                if (isOutOfStock) {
                    // Nonaktifkan tombol checkout
                    checkoutBtn.addClass('disabled');
                    checkoutBtn.attr('href', 'javascript:void(0)'); // Hapus link

                    // Tambahkan event listener untuk menampilkan pesan saat diklik
                    checkoutBtn.off('click').on('click', function(e) {
                        e.preventDefault();
                        showErrorToast(
                            'Stok salah satu produk habis. Harap hapus produk tersebut dari keranjang Anda.'
                            );
                    });

                } else {
                    // Pastikan tombol aktif jika semua stok tersedia
                    checkoutBtn.removeClass('disabled');
                    checkoutBtn.attr('href', '{{ route('cart.checkout') }}');
                    checkoutBtn.off('click'); // Hapus event listener pesan error
                }
            }

            // Panggil fungsi validasi saat halaman dimuat
            validateStock();
            // --- [AKHIR LOGIKA BARU] ---


            // Event listener untuk tombol lainnya (tidak berubah)
            $(".qty-control__increase").on("click", function() {
                $(this).closest('form').submit();
            })

            $(".qty-control__reduce").on("click", function() {
                $(this).closest('form').submit();
            })

            $(".remove-cart").on("click", function() {
                $(this).closest('form').submit();
            })
        })
    </script>
@endpush
