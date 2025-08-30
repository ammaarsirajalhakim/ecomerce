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
        // 1. Ambil input filter dari request
        $size = $request->query('size', 12);
        $order = $request->query('order', -1);
        $f_brands = $request->query('brands');
        $f_categories = $request->query('categories');
        
        // Ambil nilai min dan max untuk ditampilkan kembali di input filter
        $min_price = $request->query('min'); 
        $max_price = $request->query('max');

        // 2. Tentukan kolom dan urutan sorting
        $sortMap = [
            1 => ['created_at', 'DESC'],
            2 => ['created_at', 'ASC'],
            3 => ['sale_price', 'DESC'],
            4 => ['sale_price', 'ASC'],
        ];
        [$o_column, $o_order] = $sortMap[$order] ?? ['created_at', 'DESC'];

        // 3. Bangun query produk
        $products = Product::query()
            ->where('stock_status', 'instock')
            ->with('category')
            ->when($f_brands, function ($query, $f_brands) {
                return $query->whereIn('brand_id', explode(',', $f_brands));
            })
            ->when($f_categories, function ($query, $f_categories) {
                return $query->whereIn('category_id', explode(',', $f_categories));
            })
            // --- KODE FILTER HARGA YANG SUDAH DIPERBARUI ---
            ->when($request->has('min') && $request->has('max') && $request->min != null && $request->max != null, function ($query) use ($request) {
                return $query->where(function ($q) use ($request) {
                    $q->whereBetween('regular_price', [$request->min, $request->max])
                      ->orWhereBetween('sale_price', [$request->min, $request->max]);
                });
            })
            // --- AKHIR DARI KODE FILTER HARGA ---
            ->orderBy($o_column, $o_order)
            ->paginate($size);

        // Ambil data brand dan kategori untuk sidebar
        $brands = Brand::withCount('products')->orderBy('name', 'ASC')->get();
        $categories = Category::withCount('products')->orderBy('name', 'ASC')->get();

        // 4. Kirim data ke view
        return view('shop', [
            'products' => $products,
            'size' => $size,
            'order' => $order,
            'f_brands' => $f_brands,
            'brands' => $brands,
            'f_categories' => $f_categories,
            'categories' => $categories,
            'min_price' => $min_price,
            'max_price' => $max_price,
        ]);
    }
    
    public function product_details($product_slug)
    {
        $product = Product::where('slug', $product_slug)->with('category')->firstOrFail();
        $related_products = Product::where('category_id', $product->category_id)
                                    ->where('slug', '!=', $product_slug)
                                    ->inRandomOrder()
                                    ->limit(8)
                                    ->get();
        $prev_product = Product::where('id', '<', $product->id)->orderBy('id', 'desc')->first();
        $next_product = Product::where('id', '>', $product->id)->orderBy('id', 'asc')->first();

        return view('details', compact('product', 'related_products', 'prev_product', 'next_product'));
    }
}