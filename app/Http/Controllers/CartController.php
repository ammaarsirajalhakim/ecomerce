<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator; // Penting untuk validasi

class CartController extends Controller
{
    // Fungsi index, add_to_cart, updateQty, dll. tidak perlu diubah secara signifikan
    // ... (Fungsi-fungsi tersebut dari file Anda sudah cukup baik)

    public function index(Request $request)
    {
        $userId = Auth::id();
        $items = CartItem::where('user_id', $userId)->orderBy('created_at', 'DESC')->get();
        $productId = $request->product_id;
        $product = Product::where('id', $productId)->first();

        $subtotal = 0;

        foreach ($items as $item) {
            $price = $item->price;
            $quantity = $item->quantity;
            $subtotal += $price * $quantity;
        }

        // Cek apakah ada Voucher di session
        $discount = 0;
        if (session()->has('coupon')) {
            $this->calculateDiscount(); // Fungsi ini akan mengisi session 'discounts'
        }


        $taxRate = 0.10; // 10% pajak
        $tax = ($subtotal - $discount) * $taxRate;
        $total = ($subtotal - $discount) + $tax;

        return view('cart', compact('productId', 'product', 'items', 'subtotal', 'discount', 'tax', 'total'));
    }

    public function add_to_cart(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $productId = $request->id;
        $product = Product::find($productId);

        if (!$product || $product->quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Barang tidak tersedia atau stok tidak mencukupi!');
        }

        $item = CartItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($item) {
            if ($product->quantity < ($item->quantity + $request->quantity)) {
                return redirect()->back()->with('error', 'Stok produk tidak mencukupi!');
            }
            $item->increment('quantity', $request->quantity ?? 1);
        } else {
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $request->quantity ?? 1,
                'price' => $product->sale_price > 0 ? $product->sale_price : $product->regular_price,
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function buyNow(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->id);

        if (!$product || $product->quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi!');
        }

        session()->put('buy_now_item', [
            'product_id' => $request->id,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('cart.checkout');
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();
        $address = Address::where('user_id', $user->id)->first(); // Ambil alamat pertama, tidak harus default

        // Skenario 1: Checkout dari "Beli Sekarang"
        if (session()->has('buy_now_item')) {
            $buyNowData = session('buy_now_item');
            $product = Product::find($buyNowData['product_id']);
            $quantity = $buyNowData['quantity'];

            if (!$product) {
                session()->forget('buy_now_item');
                return redirect()->route('shop.index')->with('error', 'Produk tidak ditemukan.');
            }

            $price = $product->sale_price > 0 ? $product->sale_price : $product->regular_price;
            $subtotal = $price * $quantity;
            $taxRate = 0.10;
            $tax = $subtotal * $taxRate;
            $total = $subtotal + $tax;
            
            // Buat item tunggal untuk ditampilkan di view
            $item = new \stdClass();
            $item->product = $product;
            $item->quantity = $quantity;
            $item->subtotal = $subtotal;
            $items = collect([$item]); // Ubah menjadi collection agar view konsisten

            // Simpan data ke session agar bisa diambil saat place order
            $this->setAmountForCheckout(true);

            return view('checkout', compact('address', 'items', 'subtotal', 'tax', 'total'));
        }
        // Skenario 2: Checkout dari Keranjang Belanja
        else {
            $items = CartItem::where('user_id', $user->id)->get();
            if ($items->isEmpty()) {
                return redirect()->route('cart.index')->with('info', 'Keranjang Anda kosong.');
            }

            $subtotal = $items->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            $this->calculateDiscount(); // Hitung diskon jika ada

            if (Session::has('discounts')) {
                $subtotal = Session::get('discounts')['subtotal'];
                $tax = Session::get('discounts')['tax'];
                $total = Session::get('discounts')['total'];
            } else {
                $taxRate = 0.10;
                $tax = $subtotal * $taxRate;
                $total = $subtotal + $tax;
            }

            // Simpan data keranjang ke session checkout
            $this->setAmountForCheckout(false);

            return view('checkout', compact('address', 'items', 'subtotal', 'tax', 'total'));
        }
    }
    
    public function place_an_order(Request $request)
    {
        $user_id = Auth::id();
        $address = Address::where('user_id', $user_id)->first();
    
        // 1. Validasi Metode Pembayaran
        $request->validate(
            ['mode' => 'required|in:cod'],
            ['mode.required' => 'Silakan pilih metode pembayaran.']
        );
    
        // 2. Jika Alamat Belum Ada, Validasi dan Simpan Alamat Baru
        if (!$address) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string',
                'landmark' => 'required|string',
                'locality' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'zip' => 'required|string|max:10',
                'country' => 'required|string',
                'type' => 'required|in:Rumah,Kantor,Lainnya',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
    
            // Buat alamat baru
            $address = new Address();
            $address->user_id = $user_id;
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->address = $request->address;
            $address->landmark = $request->landmark;
            $address->locality = $request->locality;
            $address->city = $request->city;
            $address->state = $request->state;
            $address->zip = $request->zip;
            $address->country = $request->country;
            $address->type = $request->type;
            $address->isdefault = 1; // Jadikan default
            $address->save();
        }
    
        // 3. Ambil data total dari session
        $checkout = Session::get('checkout');
        if (!$checkout) {
            return redirect()->route('shop.index')->with('error', 'Sesi checkout berakhir, silakan coba lagi.');
        }
    
        // 4. Buat Order
        $order = new Order();
        $order->user_id = $user_id;
        $order->subtotal = $checkout['subtotal'];
        $order->discount = $checkout['discount'];
        $order->tax = $checkout['tax'];
        $order->total = $checkout['total'];
        $order->name = $address->name;
        $order->phone = $address->phone;
        $order->address = $address->address;
        $order->landmark = $address->landmark;
        $order->locality = $address->locality;
        $order->city = $address->city;
        $order->state = $address->state;
        $order->zip = $address->zip;
        $order->country = $address->country;
        $order->status = 'ordered'; // Status awal
        $order->save();
    
        // 5. Simpan Order Items (berdasarkan alur)
        if ($checkout['is_buy_now']) {
            $buyNowData = session('buy_now_item');
            $product = Product::find($buyNowData['product_id']);
    
            OrderItem::create([
                'product_id' => $buyNowData['product_id'],
                'order_id' => $order->id,
                'price' => $checkout['price'],
                'quantity' => $buyNowData['quantity'],
            ]);
    
            // Kurangi stok produk
            $product->quantity -= $buyNowData['quantity'];
            $product->save();
        } else {
            $cartItems = CartItem::where('user_id', $user_id)->get();
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'product_id' => $item->product_id,
                    'order_id' => $order->id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                ]);
    
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->quantity -= $item->quantity;
                    $product->save();
                }
            }
            // Hapus keranjang
            CartItem::where('user_id', $user_id)->delete();
        }
    
        // 6. Buat Transaksi
        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->order_id = $order->id;
        $transaction->mode = $request->mode;
        $transaction->status = 'pending';
        $transaction->save();
    
        // 7. Bersihkan Session
        Session::forget(['checkout', 'coupon', 'discounts', 'buy_now_item']);
        Session::put('order_id', $order->id);
    
        return redirect()->route('cart.order.confirmation');
    }
    
    public function setAmountForCheckout($isBuyNow = false)
    {
        $user_id = Auth::id();
    
        if ($isBuyNow && session()->has('buy_now_item')) {
            $buyNowData = session('buy_now_item');
            $product = Product::find($buyNowData['product_id']);
            $price = $product->sale_price > 0 ? $product->sale_price : $product->regular_price;
            $subtotal = $price * $buyNowData['quantity'];
            $tax = $subtotal * 0.10;
            $total = $subtotal + $tax;
    
            Session::put('checkout', [
                'is_buy_now' => true,
                'discount' => 0,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'price' => $price // Simpan harga per item
            ]);
        } else {
            $items = CartItem::where('user_id', $user_id)->get();
            if ($items->isEmpty()) {
                Session::forget('checkout');
                return;
            }
    
            $subtotal = $items->sum(fn($item) => $item->price * $item->quantity);
    
            if (Session::has('discounts')) {
                $discountData = Session::get('discounts');
                Session::put('checkout', [
                    'is_buy_now' => false,
                    'discount' => $discountData['discount'] ?? 0,
                    'subtotal' => $discountData['subtotal'] ?? $subtotal,
                    'tax' => $discountData['tax'] ?? ($subtotal * 0.10),
                    'total' => $discountData['total'] ?? ($subtotal + ($subtotal * 0.10))
                ]);
            } else {
                Session::put('checkout', [
                    'is_buy_now' => false,
                    'discount' => 0,
                    'subtotal' => $subtotal,
                    'tax' => $subtotal * 0.10,
                    'total' => $subtotal + ($subtotal * 0.10)
                ]);
            }
        }
    }
    
    // Fungsi lain seperti calculateDiscount, remove_coupon_code, order_confirmation, dll.
    // bisa tetap sama seperti yang Anda miliki.
    
    public function calculateDiscount()
    {
        if (!Session::has('coupon')) return;

        $user_id = Auth::id();
        $items = CartItem::where('user_id', $user_id)->get();
        $subtotal = $items->sum(fn ($item) => $item->price * $item->quantity);
        $discount = 0;
        
        $coupon = Session::get('coupon');
        if ($coupon['type'] == 'fixed') {
            $discount = $coupon['value'];
        } else {
            $discount = ($subtotal * $coupon['value']) / 100;
        }

        $subtotalAfterDiscount = $subtotal - $discount;
        $taxAfterDiscount = $subtotalAfterDiscount * 0.10; //Pajak 10 %
        $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;

        Session::put('discounts', [
            'discount' => $discount,
            'subtotal' => $subtotalAfterDiscount,
            'tax' => $taxAfterDiscount,
            'total' => $totalAfterDiscount
        ]);
    }

    public function order_confirmation()
    {
        if (Session::has('order_id')) {
            $order = Order::find(Session::get('order_id'));
            return view('order-confirmation', compact('order'));
        }
        return redirect()->route('cart.index');
    }

}