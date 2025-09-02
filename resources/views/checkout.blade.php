@extends('layouts.app')

@section('content')
    <main class="pt-20">
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
                            @if ($address)
                                <div class="col-6 text-right">
                                    <a href="{{ route('user.address.index') }}" class="btn btn-link fw-semi-bold mt-4"
                                        style="text-decoration: underline; background: none; border: none;">Ubah Alamat</a>
                                </div>
                            @endif
                        </div>

                        {{-- Jika alamat sudah ada, tampilkan detailnya --}}
                        @if ($address)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="my-account__address-list">
                                        <div class="my-account__address-item__detail">
                                            <p><strong>{{ $address->name }}</strong></p>
                                            <p>{{ $address->phone }}</p>
                                            <p>{{ $address->address }}</p>
                                            <p>{{ $address->landmark }}</p>
                                            <p>{{ $address->locality }}, {{ $address->city }}, {{ $address->state }}</p>
                                            <p>{{ $address->zip }}, {{ $address->country }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {{-- Jika alamat belum ada, tampilkan form untuk mengisinya --}}
                        @else
                            <div class="row mt-4" id="address-form-fields">
                                <p>Anda belum memiliki alamat tersimpan. Silakan isi detail di bawah ini.</p>
                                
                                <p class="mb-3">Kolom dengan tanda <span class="text-danger">*</span> wajib diisi.</p>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="name">Nama Penerima <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                    @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone">No. Telepon <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="address">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                    @error('address') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="landmark">Patokan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="landmark" name="landmark" value="{{ old('landmark') }}" required>
                                    @error('landmark') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="locality">Kelurahan/Desa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="locality" name="locality" value="{{ old('locality') }}" required>
                                    @error('locality') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label>Tipe Alamat <span class="text-danger">*</span></label>
                                    <div class="d-flex mt-2">
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="radio" name="type" id="type_rumah" value="Rumah" {{ old('type', 'Rumah') == 'Rumah' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="type_rumah">Rumah</label>
                                        </div>
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="radio" name="type" id="type_kantor" value="Kantor" {{ old('type') == 'Kantor' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="type_kantor">Kantor</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" id="type_lainnya" value="Lainnya" {{ old('type') == 'Lainnya' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="type_lainnya">Lainnya</label>
                                        </div>
                                    </div>
                                    @error('type') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="city">Kota/Kabupaten <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required>
                                    @error('city') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="state">Provinsi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}" required>
                                    @error('state') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="zip">Kode Pos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="zip" name="zip" value="{{ old('zip') }}" required>
                                    @error('zip') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="country">Negara <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="country" name="country" value="{{ old('country', 'Indonesia') }}" required>
                                    @error('country') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="save_address" id="save_address" value="1" checked>
                                        <label class="form-check-label" for="save_address">
                                            Simpan alamat ini untuk pesanan selanjutnya
                                        </label>
                                    </div>
                                </div>
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
                                                    Rp. {{ number_format($item->subtotal, 0, ',', '.') }}
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
                                                <td class="text-right">Rp. {{ number_format($subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Diskon {{ Session::get('coupon')['code'] }}</th>
                                                <td class="text-right">- Rp. {{ number_format(Session::get('discounts')['discount'], 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Subtotal Setelah Diskon</th>
                                                <td class="text-right">Rp. {{ number_format(Session::get('discounts')['subtotal'], 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Pengiriman</th>
                                                <td class="text-right">Free</td>
                                            </tr>
                                            <tr>
                                                <th>Pajak</th>
                                                <td class="text-right">Rp. {{ number_format(Session::get('discounts')['tax'], 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Total</th>
                                                <td class="text-right"><strong>Rp. {{ number_format(Session::get('discounts')['total'], 0, ',', '.') }}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <table class="checkout-totals">
                                        <tbody>
                                            <tr>
                                                <th>SUBTOTAL</th>
                                                <td class="text-right">Rp. {{ number_format($subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>PENGIRIMAN</th>
                                                <td class="text-right">Free shipping</td>
                                            </tr>
                                            <tr>
                                                <th>PAJAK</th>
                                                <td class="text-right">Rp. {{ number_format($tax, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th>TOTAL</th>
                                                <td class="text-right"><strong>Rp. {{ number_format($total, 0, ',', '.') }}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <div class="checkout__payment-methods">
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                                        id="mode3" value="cod" {{ old('mode', 'cod') == 'cod' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="mode3">
                                        Cash On Delivery (COD)
                                    </label>
                                </div>
                                <div class="policy-text">
                                    Data pribadi Anda akan digunakan untuk memproses pesanan Anda...
                                </div>
                                @error('mode')
                                    <div class="text-danger mt-1">{{ $message }}</div>
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

{{-- SEMUA JAVASCRIPT KUSTOM DIHAPUS. HANYA MENINGGALKAN BLOK KOSONG. --}}
@push('scripts')
@endpush