<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Menampilkan halaman daftar produk dengan filter dan sorting.
     */
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
            3 => ['sale_price', 'DESC'], // Harga Tertinggi -> Terendah
            4 => ['sale_price', 'ASC'],  // Harga Terendah -> Tertinggi
        ];
        [$o_column, $o_order] = $sortMap[$order] ?? ['created_at', 'DESC'];

        // 3. Bangun query produk secara bertahap
        $products = Product::query()
            // WAJIB: Hanya tampilkan produk yang siap jual.
            // Ganti 'stock_status' dan 'instock' sesuai dengan kolom dan nilai di database Anda.
            ->where('stock_status', 'instock')
            // Optimasi: Gunakan with() untuk eager load relasi category (mencegah N+1 problem)
            ->with('category')
            ->when($f_brands, function ($query, $f_brands) {
                return $query->whereIn('brand_id', explode(',', $f_brands));
            })
            ->when($f_categories, function ($query, $f_categories) {
                return $query->whereIn('category_id', explode(',', $f_categories));
            })
            ->whereBetween('regular_price', [$min_price, $max_price])
            ->orderBy($o_column, $o_order)
            ->paginate($size);

        // Ambil data brand dan kategori untuk ditampilkan di sidebar filter
        // Optimasi: Gunakan withCount() untuk menghitung jumlah produk terkait secara efisien
        $brands = Brand::withCount('products')->orderBy('name', 'ASC')->get();
        $categories = Category::withCount('products')->orderBy('name', 'ASC')->get();

        // 4. Kirim semua data yang diperlukan ke view
        return view('shop', compact(
            'products', 'size', 'order', 'f_brands', 'brands',
            'f_categories', 'categories', 'min_price', 'max_price'
        ));
    }

    /**
     * Menampilkan halaman detail produk tunggal.
     */
    public function product_details($product_slug)
    {
        // Ambil produk utama, gunakan with() untuk efisiensi
        // firstOrFail() akan otomatis menampilkan halaman 404 jika produk tidak ditemukan
        $product = Product::where('slug', $product_slug)->with('category')->firstOrFail();

        // Ambil produk terkait dari kategori yang sama (lebih relevan)
        $related_products = Product::where('category_id', $product->category_id)
                                    ->where('slug', '!=', $product_slug) // Jangan tampilkan produk yang sedang dilihat
                                    ->inRandomOrder()
                                    ->limit(8)
                                    ->get();

        // Ambil produk sebelum dan sesudahnya (untuk tombol navigasi Prev/Next)
        $prev_product = Product::where('id', '<', $product->id)->orderBy('id', 'desc')->first();
        $next_product = Product::where('id', '>', $product->id)->orderBy('id', 'asc')->first();

        // Kirim semua data yang diperlukan ke view
        return view('details', compact('product', 'related_products', 'prev_product', 'next_product'));
    }
}
