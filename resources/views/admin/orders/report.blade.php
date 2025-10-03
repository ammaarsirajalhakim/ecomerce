@extends('layouts.admin')

@section('content')
{{-- CSS Khusus untuk membuat halaman lebih responsif dan mudah dibaca --}}
<style>
    /* BARU: Styling Judul */
    .box-title {
        font-size: 1.75rem;
        font-weight: 600;
    }
    .box-subtitle {
        font-size: 1.1rem;
        color: #6c757d;
    }

    /* Styling Tabel */
    .report-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        vertical-align: middle;
        text-align: center;
    }
    .report-table td, .report-table th {
        padding: 1.1rem 0.85rem;
        font-size: 1.1rem;
        vertical-align: middle;
        white-space: nowrap; /* Mencegah teks di tabel turun baris */
    }
    .report-table tbody tr:hover {
        filter: brightness(95%);
    }
    .rank-column { width: 100px; text-align: center; }
    .sold-column { width: 180px; text-align: center; }
    .sku-column { width: 150px; }

    /* CSS UNTUK TOMBOL UNDUH */
    .btn-download {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: all 0.2s ease-in-out;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .btn-download .icon-file-text {
        font-size: 1.1rem;
    }

    /* 👇👇👇 CSS BARU UNTUK MEMPERBAIKI TABEL RESPONSIVE 👇👇👇 */
    /* Aturan ini hanya berlaku untuk layar dengan lebar 767px atau kurang */
    @media (max-width: 767px) {
        .report-table {
            min-width: 750px; /* Atur lebar minimal tabel agar bisa di-scroll */
        }
    }
</style>

<div class="main-content-inner">
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header pb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <div class="flex-grow-1 mb-3 mb-md-0">
                        <h4 class="box-title">Laporan Produk Terlaris</h4>
                        <p class="box-subtitle">Menampilkan semua produk, diurutkan dari yang paling laris hingga yang belum pernah terjual.</p>
                    </div>
                    
                    <div class="box-tools d-flex gap-3">
                        <a href="{{ route('admin.orders.report.excel') }}" class="btn btn-success btn-download">
                            <i class="icon-file-text"></i>
                            <span>Unduh Excel</span>
                        </a>
                        <a href="{{ route('admin.orders.report.pdf') }}" class="btn btn-danger btn-download">
                            <i class="icon-file-text"></i>
                            <span>Unduh PDF</span>
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table align-middle report-table">
                            <thead>
                                <tr>
                                    <th class="rank-column">Peringkat</th>
                                    <th class="sku-column">SKU</th>
                                    <th>Nama Produk</th>
                                    <th class="sold-column">Jumlah Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bestSellingProducts as $product)
                                    @php
                                        $rowClass = '';
                                        if ($product->total_quantity_sold > 5) {
                                            $rowClass = 'table-success';
                                        } elseif ($product->total_quantity_sold > 0) {
                                            $rowClass = 'table-warning';
                                        }
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td class="rank-column">
                                            {{ $loop->iteration + ($bestSellingProducts->currentPage() - 1) * $bestSellingProducts->perPage() }}
                                        </td>
                                        <td class="sku-column text-center">{{ $product->SKU }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td class="sold-column">
                                            <span class="badge {{ $product->total_quantity_sold > 0 ? 'bg-success' : 'bg-secondary' }}" style="font-size: 0.95rem; padding: 0.5em 0.75em;">
                                                {{ $product->total_quantity_sold }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <p class="text-muted">Belum ada data penjualan produk dari pesanan yang statusnya "delivered".</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 d-flex justify-content-end">
                        {{ $bestSellingProducts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection