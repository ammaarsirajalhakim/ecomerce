@extends('layouts.app')

@section('content')
    <main class="pt-20">
        <section class="shop-checkout container">
            <h2 class="page-title">Pengiriman dan Checkout</h2>
            <div class="checkout-steps">
                {{-- ... Step Indicator ... --}}
            </div>

            <form id="checkout-form" name="checkout-form" action="{{ route('cart.place.an.order') }}" method="POST">
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
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', auth()->user()->name) }}"
                                        required>
                                    @error('name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone">No. Telepon <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="address">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
                                        required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="landmark">Patokan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('landmark') is-invalid @enderror"
                                        id="landmark" name="landmark" value="{{ old('landmark') }}" required>
                                    @error('landmark')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="locality">Kelurahan/Desa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('locality') is-invalid @enderror"
                                        id="locality" name="locality" value="{{ old('locality') }}" required>
                                    @error('locality')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label>Tipe Alamat <span class="text-danger">*</span></label>
                                    <div class="d-flex mt-2">
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="radio" name="type" id="type_rumah"
                                                value="Rumah" {{ old('type', 'Rumah') == 'Rumah' ? 'checked' : '' }}
                                                required>
                                            <label class="form-check-label" for="type_rumah">Rumah</label>
                                        </div>
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="radio" name="type" id="type_kantor"
                                                value="Kantor" {{ old('type') == 'Kantor' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="type_kantor">Kantor</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" id="type_lainnya"
                                                value="Lainnya" {{ old('type') == 'Lainnya' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="type_lainnya">Lainnya</label>
                                        </div>
                                    </div>
                                    @error('type')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="city">Kota/Kabupaten <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        id="city" name="city" value="{{ old('city') }}" required>
                                    @error('city')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="state">Provinsi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('state') is-invalid @enderror"
                                        id="state" name="state" value="{{ old('state') }}" required>
                                    @error('state')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="zip">Kode Pos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('zip') is-invalid @enderror"
                                        id="zip" name="zip" value="{{ old('zip') }}" required>
                                    @error('zip')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="country">Negara <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror"
                                        id="country" name="country" value="{{ old('country', 'Indonesia') }}"
                                        required>
                                    @error('country')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
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
                                                    Rp.
                                                    {{ number_format($item->subtotal ?? $item->price * $item->quantity, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <table class="checkout-totals">
                                    <tbody>
                                        @if (Session::has('discounts'))
                                            <tr>
                                                <th>Subtotal</th>
                                                <td class="text-right">Rp.
                                                    {{ number_format(Session::get('discounts')['subtotal'] + Session::get('discounts')['discount'], 0, ',', '.') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Diskon ({{ Session::get('coupon')['code'] }})</th>
                                                <td class="text-right">- Rp.
                                                    {{ number_format(Session::get('discounts')['discount'], 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <th>SUBTOTAL</th>
                                                <td class="text-right">Rp. {{ number_format($subtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th>TOTAL</th>
                                            <td class="text-right"><strong>Rp.
                                                    {{ number_format($total, 0, ',', '.') }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                            <div class="checkout__payment-methods">
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                                        id="mode3" value="cod" checked>
                                    <label class="form-check-label" for="mode3">
                                        Cash On Delivery (COD)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode"
                                        id="mode4" value="transfer">
                                    <label class="form-check-label" for="mode4">
                                        Transfer Bank
                                    </label>
                                </div>
                                <div class="policy-text">
                                    Data pribadi Anda akan digunakan untuk memproses pesanan Anda...
                                </div>
                                @error('mode')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="button" id="pay-button" class="btn btn-primary btn-checkout">BUAT
                                PESANAN</button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </main>

    @push('scripts')
        {{-- 1. Muat library Snap.js dari Midtrans --}}
        <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>

        <script type="text/javascript">
            // Pastikan jQuery sudah dimuat sebelum blok ini
            $(document).ready(function() {
                var payButton = document.getElementById('pay-button');

                payButton.addEventListener('click', function() {
                    payButton.disabled = true;
                    payButton.innerHTML = 'Memproses...';

                    $.ajax({
                        url: "{{ route('cart.place.an.order') }}",
                        method: 'POST',
                        data: $('#checkout-form').serialize(),
                        cache: false,
                        success: function(data) {
                            if (data.snap_token) {
                                snap.pay(data.snap_token, {
                                    onSuccess: function(result) {
                                        sendPaymentResult(result);
                                    },
                                    onPending: function(result) {
                                        sendPaymentResult(result);
                                    },
                                    onError: function(result) {
                                        alert("Pembayaran Gagal!");
                                        payButton.disabled = false;
                                        payButton.innerHTML = 'BUAT PESANAN';
                                    },
                                    onClose: function() {
                                        alert('Anda menutup popup pembayaran.');
                                        payButton.disabled = false;
                                        payButton.innerHTML = 'BUAT PESANAN';
                                    }
                                });
                            } else {
                                alert(data.error || 'Gagal mendapatkan token pembayaran.');
                                payButton.disabled = false;
                                payButton.innerHTML = 'BUAT PESANAN';
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            alert("Terjadi kesalahan. Silakan coba lagi.");
                            payButton.disabled = false;
                            payButton.innerHTML = 'BUAT PESANAN';
                        }
                    });
                });

                // PASTIKAN FUNGSI INI ADA DI DALAM SCRIPT TAG YANG SAMA
                function sendPaymentResult(result) {
                    $.ajax({
                        url: "{{ route('payment.success') }}",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            result: result
                        },
                        success: function() {
                            window.location.href = "{{ route('cart.order.confirmation') }}";
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            alert('Gagal memproses hasil pembayaran di server.');
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
