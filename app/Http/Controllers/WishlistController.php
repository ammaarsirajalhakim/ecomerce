<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $items = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('wishlist', compact('items'));
    }

    public function add_to_wishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Silakan login terlebih dahulu.'], 401);
        }

        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $request->id,
        ]);

        // Hitung jumlah wishlist terbaru setelah menambahkan item
        $wishlistCount = Auth::user()->wishlists()->count();

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil ditambahkan ke wishlist.',
            'count' => $wishlistCount // Kirim jumlah terbaru ke JavaScript
        ]);
    }

    public function remove_item($product_id)
    {
        if (!Auth::check()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $deleted = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->delete();
        
        // Hitung jumlah wishlist terbaru setelah menghapus item
        $wishlistCount = Auth::user()->wishlists()->count();

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil dihapus dari wishlist.',
            'count' => $wishlistCount // Kirim jumlah terbaru ke JavaScript
        ]);
    }

    public function empty_wishlist()
    {
        Wishlist::where('user_id', Auth::id())->delete();
        return redirect()->back()->with('success', 'Wishlist berhasil dikosongkan.');
    }

    public function move_to_cart($id)
    {
        $wishlistItem = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        CartItem::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $wishlistItem->product_id,
            ],
            [
                'quantity' => 1,
                'price' => $wishlistItem->product->sale_price ?? $wishlistItem->product->regular_price,
            ]
        );

        $wishlistItem->delete();
        return redirect()->back()->with('success', 'Produk berhasil dipindahkan ke keranjang.');
    }
}