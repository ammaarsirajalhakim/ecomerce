<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Surfsidemedia\Shoppingcart\Facades\Cart;

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
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $productId = $request->id;

        Wishlist::firstOrCreate([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        // Cart::instance('wishlist')
        //     ->add($productId, $request->name, $request->quantity, $request->price)
        //     ->associate('App\Models\Product');

        return redirect()->back();
    }


    public function remove_item($product_id)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->delete();

        return back();
    }


    public function empty_wishlist()
    {
        Cart::instance('wishlist')->destroy();
        return redirect()->back();
    }

    public function move_to_cart($id)
    {
        $wishlistItem = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Tambahkan ke cart_items
        CartItem::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $wishlistItem->product_id,
            ],
            [
                'quantity' => 1,
                'price' => $wishlistItem->product->active_price,
            ]
        );

        // Hapus dari wishlist
        $wishlistItem->delete();
        return redirect()->back();
    }
}
