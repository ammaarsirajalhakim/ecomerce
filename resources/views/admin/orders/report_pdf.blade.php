<!DOCTYPE html>
<html>
<head>
    <title>Laporan Produk Terlaris</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        table { width: 100%; border-collapse: collapse; }
        th, td { 
            border: 1px solid #dddddd; 
            text-align: left; 
            padding: 8px; 
            font-size: 12px;
        }
        thead th { 
            background-color: #f2f2f2; 
            font-weight: bold; 
        }
        h1 { 
            text-align: center; 
            font-size: 18px; 
            margin-bottom: 20px;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Laporan Produk Terlaris</h1>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>SKU</th>
                <th>Nama Produk</th>
                <th style="text-align: center;">Jumlah Terjual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bestSellingProducts as $product)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ $product->SKU }}</td>
                    <td>{{ $product->name }}</td>
                    <td style="text-align: center;">{{ $product->total_quantity_sold }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data penjualan yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>