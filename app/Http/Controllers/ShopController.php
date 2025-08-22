<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil input filter dari request dengan nilai default
        $size = $request->query('size', 12);
        $order = $request->query('order', -1);
        $f_brands = $request->query('brands');
        $f_categories = $request->query('categories');
        $min_price = $request->query('min', 1);
        // Set max_price ke nilai yang lebih tinggi untuk memastikan semua produk masuk jika tidak difilter
        $max_price = $request->query('max', 99999999); 

        // 2. Tentukan kolom dan urutan sorting
        $sortMap = [
            1 => ['created_at', 'DESC'], // Terbaru
            2 => ['created_at', 'ASC'],  // Terlama
            // Perbaikan: Sorting harga harus berdasarkan harga efektif (sale_price jika ada)
            3 => ['sale_price', 'DESC'], // Harga Tertinggi -> Terendah
            4 => ['sale_price', 'ASC'],  // Harga Terendah -> Tertinggi
        ];
        [$o_column, $o_order] = $sortMap[$order] ?? ['created_at', 'DESC'];

        // 3. Bangun query produk secara bertahap
        $products = Product::query()
            // WAJIB: Hanya tampilkan produk dengan status 'active' atau 'published'
            // Ganti 'active' sesuai dengan nama status di database Anda.
            ->where('stock_status', 'instock') 
            // Optimasi: Gunakan with() untuk mengatasi N+1 query problem pada relasi category
            ->with('category')
            ->when($f_brands, function ($query, $f_brands) {
                return $query->whereIn('brand_id', explode(',', $f_brands));
            })
            ->when($f_categories, function ($query, $f_categories) {
                return $query->whereIn('category_id', explode(',', $f_categories));
            })
            // Filter harga yang lebih akurat
            ->whereBetween('regular_price', [$min_price, $max_price])
            ->orderBy($o_column, $o_order)
            ->paginate($size);

        // Ambil data brand dan kategori untuk ditampilkan di sidebar filter (dengan jumlah produk)
        // Optimasi: Gunakan withCount() agar lebih efisien
        $brands = Brand::withCount('products')->orderBy('name', 'ASC')->get();
        $categories = Category::withCount('products')->orderBy('name', 'ASC')->get();

        // 4. Kirim semua data yang diperlukan ke view
        return view('shop', compact(
            'products', 'size', 'order', 'f_brands', 'brands',
            'f_categories', 'categories', 'min_price', 'max_price'
        ));
    }
    
    // ... method product_details() tetap sama ...
    public function product_details($product_slug)
    {
        // ... (kode tidak berubah)
    }
}