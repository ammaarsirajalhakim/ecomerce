@extends('layouts.app')

@section('content')
<main class="pt-20">
  <section class="shop-checkout container">
    {{-- ===== PAGE HEADER ===== --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
      <div>
        <h2 class="page-title mb-1">Pengiriman & Checkout</h2>
        <div class="text-muted small">Periksa alamat, ongkos kirim, lalu pilih metode pembayaran.</div>
      </div>
      <div class="checkout-steps d-flex align-items-center gap-2">
        {{-- ... Step Indicator (tetap) ... --}}
      </div>
    </div>

    <form id="checkout-form" name="checkout-form" action="{{ route('cart.place.an.order') }}" method="POST">
      @csrf

      <div class="row g-4">
        {{-- ===================== LEFT: DETAIL PENGIRIMAN ===================== --}}
        <div class="col-lg-7">
          <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between py-3">
              <h5 class="mb-0">Detail Pengiriman</h5>
              @if ($address)
                <a href="{{ route('user.address.index') }}" class="btn btn-link fw-semi-bold p-0">
                  Ubah Alamat
                </a>
              @endif
            </div>

            <div class="card-body">
              {{-- Jika alamat sudah ada, tampilkan --}}
              @if ($address)
                <div class="rounded-3 p-3 mb-3 bg-light border address-box">
                  <div class="d-flex align-items-start gap-3">
                    <div class="badge bg-primary-subtle text-dark px-3 py-2 rounded-pill">Alamat Utama</div>
                    <div class="small text-muted">Pastikan detailnya sudah benar sebelum lanjut pembayaran.</div>
                  </div>
                  <hr class="my-3">
                  <div class="my-account__address-item__detail">
                    <p class="mb-1 fw-semibold">{{ $address->name }}</p>
                    <p class="mb-1">{{ $address->phone }}</p>
                    <p class="mb-1">{{ $address->address }}</p>
                    <p class="mb-1">{{ $address->landmark }}</p>
                    <p class="mb-1">{{ $address->locality }}, {{ $address->city }}, {{ $address->state }}</p>
                    <p class="mb-0">{{ $address->zip }}, {{ $address->country }}</p>
                  </div>
                </div>
              @else
                {{-- Jika alamat belum ada, form input (tetap) --}}
                <div id="address-form-fields" class="mt-1">
                  <div class="alert alert-info mb-4">
                    <div class="fw-semibold mb-1">Belum ada alamat tersimpan</div>
                    <div>Isi detail berikut. Kolom bertanda <span class="text-danger">*</span> wajib diisi.</div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label for="name" class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                      <input type="text" class="form-control @error('name') is-invalid @enderror"
                        id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                      @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                      <label for="phone" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                      <input type="text" class="form-control @error('phone') is-invalid @enderror"
                        id="phone" name="phone" value="{{ old('phone') }}" required>
                      @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 mb-3">
                      <label for="address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                      <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                      @error('address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                      <label for="landmark" class="form-label">Patokan <span class="text-danger">*</span></label>
                      <input type="text" class="form-control @error('landmark') is-invalid @enderror"
                        id="landmark" name="landmark" value="{{ old('landmark') }}" required>
                      @error('landmark')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                      <label for="locality" class="form-label">Kelurahan/Desa <span class="text-danger">*</span></label>
                      <input type="text" class="form-control @error('locality') is-invalid @enderror"
                        id="locality" name="locality" value="{{ old('locality') }}" required>
                      @error('locality')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 mb-3">
                      <label class="form-label d-block">Tipe Alamat <span class="text-danger">*</span></label>
                      <div class="d-flex flex-wrap gap-3 mt-1">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="type" id="type_rumah"
                            value="Rumah" {{ old('type', 'Rumah') == 'Rumah' ? 'checked' : '' }} required>
                          <label class="form-check-label" for="type_rumah">Rumah</label>
                        </div>
                        <div class="form-check">
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
                      @error('type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                      <label for="city" class="form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                      <input type="text" class="form-control @error('city') is-invalid @enderror"
                        id="city" name="city" value="{{ old('city') }}" required>
                      @error('city')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                      <label for="state" class="form-label">Provinsi <span class="text-danger">*</span></label>
                      <input type="text" class="form-control @error('state') is-invalid @enderror"
                        id="state" name="state" value="{{ old('state') }}" required>
                      @error('state')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                      <label for="zip" class="form-label">Kode Pos <span class="text-danger">*</span></label>
                      <input type="text" class="form-control @error('zip') is-invalid @enderror"
                        id="zip" name="zip" value="{{ old('zip') }}" required>
                      @error('zip')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                      <label for="country" class="form-label">Negara <span class="text-danger">*</span></label>
                      <input type="text" class="form-control @error('country') is-invalid @enderror"
                        id="country" name="country" value="{{ old('country', 'Indonesia') }}" required>
                      @error('country')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                  </div>
                </div>
              @endif
            </div>
          </div>

          {{-- ===================== SHIPPING / ONGKIR ===================== --}}
          <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0 py-3">
              <h5 class="mb-0">Ekspedisi & Ongkos Kirim</h5>
            </div>
            <div class="card-body">
              {{-- Hidden hasil pilihan ongkir (untuk dikirim ke server) --}}
              <input type="hidden" name="shipping_courier" id="shipping_courier">
              <input type="hidden" name="shipping_service" id="shipping_service">
              <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
              <input type="hidden" name="shipping_etd" id="shipping_etd">

              {{-- Simpan total barang (sudah termasuk diskon) untuk kalkulasi client-side --}}
              <input type="hidden" id="base_total_without_shipping"
                value="@if(Session::has('discounts')){{ (Session::get('discounts')['subtotal'] - Session::get('discounts')['discount']) }}@else{{ $total }}@endif">

              <div class="mb-3">
                <div class="d-flex flex-wrap gap-3">
                  <div class="custom-control custom-radio">
                    <input type="radio" id="courier_jne" name="courier" value="jne" class="custom-control-input" checked>
                    <label class="custom-control-label" for="courier_jne">JNE</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input type="radio" id="courier_pos" name="courier" value="pos" class="custom-control-input">
                    <label class="custom-control-label" for="courier_pos">POS Indonesia</label>
                  </div>
                  <div class="custom-control custom-radio">
                    <input type="radio" id="courier_jnt" name="courier" value="jnt" class="custom-control-input">
                    <label class="custom-control-label" for="courier_jnt">J&amp;T</label>
                  </div>
                </div>
              </div>

              <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-primary" id="btnCheckOngkir">
                  Cek Ongkos Kirim
                </button>
                <div id="shippingNote" class="text-muted small"></div>
              </div>

              <div id="shippingOptions" class="mt-3"></div>

              {{-- elemen lama (biarkan ada) --}}
              <ul id="ongkirResult" class="list-group d-none"></ul>
              <div id="ongkirEmpty" class="alert alert-warning d-none mt-3 mb-0">Tarif tidak tersedia.</div>
            </div>
          </div>
        </div>

        {{-- ===================== RIGHT: RINGKASAN & PEMBAYARAN ===================== --}}
        <div class="col-lg-5">
          <div class="position-lg-sticky top-lg-20">
            {{-- RINGKASAN PESANAN --}}
            <div class="card shadow-sm border-0 mb-4">
              <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">Ringkasan Pesanan</h5>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table align-middle checkout-cart-items mb-3">
                    <thead class="small text-muted">
                      <tr>
                        <th class="border-0">Produk</th>
                        <th class="border-0 bg-transparent text-end">Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($items as $item)
                        <tr>
                          <td class="border-0">
                            <div class="fw-semibold">{{ $item->product->name }}</div>
                            <div class="text-muted small">x {{ $item->quantity }}</div>
                          </td>
                          <td class="border-0 text-end">
                            Rp. {{ number_format($item->subtotal ?? $item->price * $item->quantity, 0, ',', '.') }}
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>

                <hr>

                <div class="table-responsive">
                  <table class="table checkout-totals mb-0">
                    <tbody>
                      @if (Session::has('discounts'))
                        <tr>
                          <th class="border-0">Subtotal</th>
                          <td class="border-0 text-end" id="subtotal_products_text">Rp. {{ number_format(Session::get('discounts')['subtotal'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                          <th class="border-0">Diskon ({{ Session::get('coupon')['code'] }})</th>
                          <td class="border-0 text-end">- Rp. {{ number_format(Session::get('discounts')['discount'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                          <th class="border-0">Total Barang</th>
                          <td class="border-0 text-end" id="total_products_text">
                            Rp. {{ number_format(Session::get('discounts')['subtotal'] - Session::get('discounts')['discount'], 0, ',', '.') }}
                          </td>
                        </tr>
                      @else
                        <tr>
                          <th class="border-0">Total Barang</th>
                          <td class="border-0 text-end" id="total_products_text">Rp. {{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                      @endif

                      {{-- Tambahan: Ongkos Kirim (dinamis) --}}
                      <tr>
                        <th class="border-0">Ongkos Kirim</th>
                        <td class="border-0 text-end" id="shipping_cost_text">Rp. 0</td>
                      </tr>

                      <tr class="fw-semibold">
                        <th class="border-0">Total Bayar</th>
                        <td class="border-0 text-end" id="grand_total_text">
                          {{-- default = total barang (tanpa ongkir) --}}
                          @if (Session::has('discounts'))
                            Rp. {{ number_format(Session::get('discounts')['subtotal'] - Session::get('discounts')['discount'], 0, ',', '.') }}
                          @else
                            Rp. {{ number_format($total, 0, ',', '.') }}
                          @endif
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                {{-- Hidden agar server bisa terima nilai ini juga bila perlu --}}
                <input type="hidden" name="products_total_without_shipping"
                  id="products_total_without_shipping_hidden"
                  value="@if(Session::has('discounts')){{ (Session::get('discounts')['subtotal'] - Session::get('discounts')['discount']) }}@else{{ $total }}@endif">
                <input type="hidden" name="grand_total_client" id="grand_total_client_hidden" value="@if(Session::has('discounts')){{ (Session::get('discounts')['subtotal'] - Session::get('discounts')['discount']) }}@else{{ $total }}@endif">

              </div>
            </div>

            {{-- METODE PEMBAYARAN --}}
            <div class="card shadow-sm border-0 mb-4">
              <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">Metode Pembayaran</h5>
              </div>
              <div class="card-body">
                <div class="vstack gap-3">
                  <div class="form-check">
                    <input class="form-check-input form-check-input_fill" type="radio" name="mode" id="mode3" value="cod" checked>
                    <label class="form-check-label" for="mode3">Cash On Delivery (COD)</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input form-check-input_fill" type="radio" name="mode" id="mode4" value="transfer">
                    <label class="form-check-label" for="mode4">Transfer Bank</label>
                  </div>
                </div>

                <div class="policy-text small text-muted mt-3">
                  Data pribadi Anda akan digunakan untuk memproses pesanan Anda...
                </div>
                @error('mode')<div class="text-danger mt-2">{{ $message }}</div>@enderror

                <button type="submit" id="place-order-btn" class="btn btn-primary" disabled>
        Buat Pesanan
    </button>
              </div>
            </div>
          </div>
        </div>
      </div> {{-- /row --}}
    </form>
  </section>
</main>

{{-- ========== STYLE KHUSUS TAMPILAN (tidak mengubah fungsionalitas) ========== --}}
@push('styles')
<style>
  .top-lg-20{ top:20px; }
  @media (min-width: 992px){
    .position-lg-sticky{ position: sticky; }
  }
  .page-title{ font-weight:700; }
  .checkout-cart-items th, .checkout-cart-items td{ background:transparent !important; }
  .checkout-totals th{ width:50%; }
  .custom-control.custom-radio{ padding-left: 1.8rem; }
  .custom-control-input:checked~.custom-control-label{ font-weight:600; }
  #shippingOptions .custom-control{
    border:1px solid rgba(0,0,0,.08);
    border-radius:.5rem;
    padding:.75rem .75rem .75rem 2.25rem;
    transition:all .15s ease;
  }
  #shippingOptions .custom-control:hover{
    background: rgba(0,0,0,.02);
    border-color: rgba(0,0,0,.15);
  }
  .btn-primary{ border-radius:.75rem; }
  .card{ border-radius:1rem; }
  .card-header{ border-bottom:1px solid rgba(0,0,0,.06)!important; }
</style>
@endpush

{{-- ====== SCRIPT YANG SUDAH ADA (TETAP) ====== --}}
@push('scripts')
  {{-- Midtrans Snap --}}
  <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      let pendingOrderId = null;

      $('#checkout-form').on('submit', function(event) {
        var payButton = $('#pay-button');
        var selectedPaymentMethod = $('input[name="mode"]:checked').val();

        if (selectedPaymentMethod === 'transfer') {
          event.preventDefault();

          payButton.prop('disabled', true).text('Memproses...');

          $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            cache: false,
            success: function(data) {
              if (data.error || !data.snap_token) {
                alert(data.error || 'Gagal mendapatkan token pembayaran.');
                payButton.prop('disabled', false).text('Buat Pesanan');
                return;
              }

              pendingOrderId = data.order_id;

              snap.pay(data.snap_token, {
                onSuccess: function(result) {
                  pendingOrderId = null;
                  sendPaymentResult(result);
                },
                onPending: function(result) {
                  pendingOrderId = null;
                  sendPaymentResult(result);
                },
                onError: function() {
                  alert("Pembayaran Gagal!");
                  cancelOrder(pendingOrderId);
                  payButton.prop('disabled', false).text('Buat Pesanan');
                },
                onClose: function() {
                  if (pendingOrderId) {
                    cancelOrder(pendingOrderId);
                  }
                  payButton.prop('disabled', false).text('Buat Pesanan');
                }
              });
            },
            error: function(xhr) {
              console.error(xhr.responseText);
              alert("Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.");
              payButton.prop('disabled', false).text('Buat Pesanan');
            }
          });
        } else {
          payButton.prop('disabled', true).text('Memproses...');
        }
      });

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

      function cancelOrder(orderId) {
        if (!orderId) return;
        $.ajax({
          url: "{{ route('cart.order.cancel') }}",
          method: 'POST',
          data: { _token: "{{ csrf_token() }}", order_id: orderId },
          success: function() { /* no-op UI */ },
          error: function(xhr) { console.error(xhr.responseText); }
        });
      }
    });
  </script>

  {{-- Variabel RO dari server --}}
  <script>
    window.RO_CHECK_URL = "{{ route('ro.check') }}";        // endpoint cek ongkir anda
    window.RO_DEST      = @json($address->district_id ?? ''); // id kecamatan tujuan
    window.RO_WEIGHT_G  = @json((int)($totalWeightG ?? 1));   // total berat gram
  </script>

  {{-- Ongkir checker + KALKULASI RINGKASAN (dinamis) --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const btn  = document.getElementById('btnCheckOngkir');
      const box  = document.getElementById('shippingOptions');
      const note = document.getElementById('shippingNote');

      const hidCourier = document.getElementById('shipping_courier');
      const hidService = document.getElementById('shipping_service');
      const hidCost    = document.getElementById('shipping_cost');
      const hidEtd     = document.getElementById('shipping_etd');

      // label ringkasan
      const lblShip = document.getElementById('shipping_cost_text');
      const lblGrand= document.getElementById('grand_total_text');

      const baseWithoutShipInput = document.getElementById('base_total_without_shipping');
      const hiddenProductsTotal  = document.getElementById('products_total_without_shipping_hidden');
      const hiddenGrandClient    = document.getElementById('grand_total_client_hidden');
      const placeBtn = document.getElementById('place-order-btn');

      // helper rupiah
      function rupiah(n){
        return new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(Number(n||0));
      }

      function getBaseTotal(){
        // pakai hidden base (total barang sudah diskon)
        const v = Number(baseWithoutShipInput?.value || hiddenProductsTotal?.value || 0);
        return isNaN(v) ? 0 : v;
      }

      function recalcGrand(){
        const base = getBaseTotal();
        const ship = Number(hidCost?.value || 0);
        const grand = base + ship;

        if (lblShip)  lblShip.textContent  = rupiah(ship);
        if (lblGrand) lblGrand.textContent = rupiah(grand);
        if (hiddenGrandClient) hiddenGrandClient.value = grand;
      }

      function applySelection(radio){
        if (!radio) return;
        if (hidCourier) hidCourier.value = radio.dataset.courier || '';
        if (hidService) hidService.value = radio.dataset.service || '';
        if (hidCost)    hidCost.value    = radio.dataset.price || '0';
        if (hidEtd)     hidEtd.value     = radio.dataset.etd || '';
        recalcGrand();
      }

      const token = document.querySelector('meta[name="csrf-token"]')?.content || '';
      const destinationDistrictId = window.RO_DEST || '';
      const totalWeightGram       = Number(window.RO_WEIGHT_G || 1) || 1;
      const getCourier = () => document.querySelector('input[name="courier"]:checked')?.value || 'jne';

      async function checkOngkir(){
        box.innerHTML = '';
        note.textContent = '';

        if (!destinationDistrictId) {
          note.textContent = 'Alamat tujuan belum lengkap (kecamatan belum dipilih).';
          return;
        }

        const payload = new URLSearchParams({
          district_id: String(destinationDistrictId),
          weight: String(Math.max(1, +totalWeightGram || 1)),
          courier: String(getCourier())
        });

        let res;
        try {
          res = await fetch(window.RO_CHECK_URL, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': token,
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: payload.toString()
          });
        } catch(e) {
          note.textContent = 'Tidak bisa menghubungi server ongkir.';
          if (placeBtn) placeBtn.disabled = true;      // << matikan saat error jaringan
          return;
        }

        let data;
        try { data = await res.json(); } catch(e) { data = {}; }

        const results = Array.isArray(data?.results) ? data.results : (Array.isArray(data) ? data : []);
        renderServices(results, data?.message);
      }

      function renderServices(results, apiMessage){
        box.innerHTML = '';
        if (!Array.isArray(results) || results.length === 0){
          box.innerHTML = '<div class="alert alert-warning mb-0">Tarif tidak tersedia.</div>';
          if (apiMessage) note.textContent = apiMessage;
          // reset ongkir jadi 0 jika gagal
          if (hidCost) hidCost.value = 0;
          recalcGrand();
          if (placeBtn) placeBtn.disabled = true; // << matikan saat gagal
          return;
        }

        // Format flat {service, description, cost, etd}
        if ('cost' in (results[0] || {})) {
          const frag = document.createDocumentFragment();
          const courierCode = getCourier();

          results.forEach((r, idx) => {
            const svc   = r.service || '';
            const desc  = r.description || '';
            const price = Number(r.cost) || 0;
            const etd   = r.etd || '';
            const id    = `ship_${svc}_${idx}`;

            const wrap = document.createElement('div');
            wrap.className = 'custom-control custom-radio mb-2';
            wrap.innerHTML =
              '<input type="radio" id="'+id+'" name="shipping_pick" class="custom-control-input"'+
              ' data-courier="'+courierCode+'" data-service="'+svc+'" data-price="'+price+'" data-etd="'+etd+'">'+
              '<label class="custom-control-label" for="'+id+'">'+
                '<strong>'+svc+'</strong> — '+desc+' · '+rupiah(price)+(etd ? ' · ETD '+etd : '')+
              '</label>';
            frag.appendChild(wrap);
          });

          box.appendChild(frag);

          const radios = box.querySelectorAll('input[name="shipping_pick"]');
          if (radios.length) {
            let pick = radios[0];
            radios.forEach(r => { if (+r.dataset.price < +pick.dataset.price) pick = r; });
            pick.checked = true;
            applySelection(pick);
            if (placeBtn) placeBtn.disabled = false;  // << hidupkan setelah sukses pilih
          }

          box.addEventListener('change', (e) => {
            if (e.target && e.target.name === 'shipping_pick') applySelection(e.target);
            if (placeBtn) placeBtn.disabled = false; // << hidupkan saat user ganti opsi
          });

          return;
        }

        // Format klasik RajaOngkir: [{code, costs:[{service, description, cost:[{value,etd}]}]}]
        const first = results[0] || {};
        const courierCode = first.code || getCourier();
        const costs = Array.isArray(first.costs) ? first.costs : [];

        if (!costs.length){
          box.innerHTML = '<div class="alert alert-warning mb-0">Layanan tidak ditemukan untuk kurir terpilih. Coba kurir lain.</div>';
          if (hidCost) hidCost.value = 0;
          recalcGrand();
          return;
        }

        const frag = document.createDocumentFragment();
        costs.forEach((c, idx) => {
          const svc   = c?.service || '';
          const desc  = c?.description || '';
          const price = (c?.cost?.[0]?.value) ?? 0;
          const etd   = (c?.cost?.[0]?.etd) ?? '';
          const id    = `ship_${svc}_${idx}`;

          const wrap = document.createElement('div');
          wrap.className = 'custom-control custom-radio mb-2';
          wrap.innerHTML =
            '<input type="radio" id="'+id+'" name="shipping_pick" class="custom-control-input"'+
            ' data-courier="'+courierCode+'" data-service="'+svc+'" data-price="'+price+'" data-etd="'+etd+'">'+
            '<label class="custom-control-label" for="'+id+'">'+
              '<strong>'+svc+'</strong> — '+desc+' · '+rupiah(price)+(etd ? ' · ETD '+etd+' hari' : '')+
            '</label>';
          frag.appendChild(wrap);
        });
        box.appendChild(frag);

        const radios2 = box.querySelectorAll('input[name="shipping_pick"]');
        if (radios2.length) {
          let pick = radios2[0];
          radios2.forEach(r => { if (+r.dataset.price < +pick.dataset.price) pick = r; });
          pick.checked = true;
          applySelection(pick);
          if (placeBtn) placeBtn.disabled = false;    // << hidupkan setelah sukses pilih
        }

        box.addEventListener('change', (e) => {
          if (e.target && e.target.name === 'shipping_pick') applySelection(e.target);
          if (placeBtn) placeBtn.disabled = false;  // << hidupkan saat user ganti opsi
        });
      }

      // tombol cek ongkir
      document.getElementById('btnCheckOngkir')?.addEventListener('click', checkOngkir);

      // inisialisasi tampilan default
      recalcGrand();
      document.querySelector('form#checkout-form')?.addEventListener('submit', (e) => {
  if (!hidCost || Number(hidCost.value || 0) <= 0) {
    e.preventDefault();
    note.textContent = 'Silakan cek dan pilih ongkos kirim terlebih dahulu.';
    if (placeBtn) placeBtn.disabled = true;
  }
});

    });
  </script>
@endpush
@endsection
