@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <h2 class="page-title">Pengiriman dan Checkout</h2>
            <div class="checkout-steps">
                <a href="{{ route('cart.index') }}" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">01</span>
                    <span class="checkout-steps__item-title">
                        <span>Tas Belanja</span>
                        <em>Kelola Daftar Item Anda</em>
                    </span>
                </a>
                <a href="javascript:void(0)" class="checkout-steps__item active">
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
            <form name="checkout-form" action="{{ route('cart.place.an.order') }}" method="POST">
                @csrf
                <div class="checkout-form">
                    <div class="billing-info__wrapper">
                        <div class="row">
                            <div class="col-6">
                                <h4>DETAIL PENGIRIMAN</h4>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('user.address.index') }}" class="btn btn-link fw-semi-bold mt-4"
                                    style="text-decoration: underline; background: none; border: none;">Ubah Alamat</a>
                            </div>
                        </div>
                        @if ($address)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="my-account__address-list">
                                        <div class="my-account__address-item__detail">
                                            <p>{{ $address->name }}</p>
                                            <p>{{ $address->address }}</p>
                                            <p>{{ $address->landmark }}</p>
                                            <p>{{ $address->locality }},{{ $address->state }},{{ $address->city }},{{ $address->country }}
                                            </p>
                                            <p>{{ $address->zip }}</p>
                                            <p>{{ $address->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row mt-5">
                                @include('partials.address-form')
                            </div>
                        @endif
                    </div>
                    <div class="checkout__totals-wrapper">
                        <div class="sticky-content">
                            <div class="checkout__totals">
                                <h3>Pesanan Anda</h3>
                                <table class="checkout-cart-items">
                                    <thead>
                                        <tr>
                                            <th>PRODUK</th>
                                            <th class="text-right">SUBTOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr>
                                                <td>
                                                    {{ $item->product->name }} x {{ $item->quantity }}
                                                </td>
                                                <td class="text-right">
                                                    Rp. {{ $item->subtotal }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if (Session::has('discounts'))
                                    <table class="checkout-totals">
                                        <tbody>
                                            <tr>
                                                <th>Subtotal</th>
                                                <td class="text-right">Rp. {{ $subtotal }}</td>
                                            </tr>
                                            <tr>
                                                <th>Diskon {{ Session::get('coupon')['code'] }}</th>
                                                <td class="text-right">Rp. {{ Session::get('discounts')['discount'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Subtotal Setelah Diskon</th>
                                                <td class="text-right">Rp. {{ Session::get('discounts')['subtotal'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Pengiriman</th>
                                                <td class="text-right">Free</td>
                                            </tr>
                                            <tr>
                                                <th>Pajak</th>
                                                <td class="text-right">Rp. {{ Session::get('discounts')['tax'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td class="text-right">Rp. {{ Session::get('discounts')['total'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <table class="checkout-totals">
                                        <tbody>
                                            <tr>
                                                <th>SUBTOTAL</th>
                                                <td class="text-right">Rp. {{ $subtotal }}</td>
                                            </tr>
                                            <tr>
                                                <th>PENGIRIMAN</th>
                                                <td class="text-right">Free shipping</td>
                                            </tr>
                                            <tr>
                                                <th>PAJAK</th>
                                                <td class="text-right">Rp. {{ $tax }}</td>
                                            </tr>
                                            <tr>
                                                <th>TOTAL</th>
                                                <td class="text-right">Rp. {{ $total }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <div class="checkout__payment-methods">
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                                        id="mode1" value="card" {{ old('mode') == 'card' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="mode1">
                                        Debit atau Kartu Kredit
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                                        id="mode2" value="paypal" {{ old('mode') == 'paypal' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="mode2">
                                        Paypal
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                                        id="mode3" value="cod" {{ old('mode') == 'cod' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="mode3">
                                        Cash On Delivery (COD)
                                    </label>
                                </div>
                                <div class="policy-text">
                                    Data pribadi Anda akan digunakan untuk memproses pesanan Anda, mendukung pengalaman Anda
                                    di seluruh situs web ini, dan untuk tujuan lain yang dijelaskan dalam <a
                                        href="terms.html" target="_blank">privacy
                                        policy</a>.
                                </div>
                                @error('mode')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-checkout">BUAT PESANAN</button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </main>
@endsection
