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
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
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
            $coupon = session('coupon');
            if (isset($coupon['type'], $coupon['value'])) {
                if ($coupon['type'] === 'fixed') {
                    $discount = $coupon['value'];
                } elseif ($coupon['type'] === 'percent') {
                    $discount = ($coupon['value'] / 100) * $subtotal;
                }
            }
            $this->calculateDiscount();
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

        // Validasi Stok
        if (!$product || $product->quantity <= 0) {
            return redirect()->route('shop.index')->with('error', 'Barang tidak tersedia atau stok telah habis!');
        }

        // Cek apakah produk sudah ada di cart
        $item = CartItem::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($item) {
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

    public function updateQty(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $item = CartItem::findOrFail($id);
        $item->quantity = $request->quantity;
        $item->save();

        return redirect()->back();
    }

    public function increase_cart_quantity($id)
    {
        $product = CartItem::find($id);
        $product->quantity += 1;
        $product->save();
        return redirect()->back();
    }

    public function decrease_cart_quantity($id)
    {
        $product = CartItem::find($id); // <= gunakan find()
        if ($product->quantity <= 1) {
            // Optional: hapus dari keranjang kalau qty 0
            $product->delete();
            return redirect()->back();
        }
        $product->quantity -= 1;
        $product->save();
        return redirect()->back();
    }

    public function remove_item($id)
    {
        $product = CartItem::find($id);
        $product->delete();
        // Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function empty_cart()
    {
        CartItem::where('user_id', Auth::id())->delete();
        // Cart::instance('cart')->destroy();
        return redirect()->back();
    }

    public function apply_coupon_code(Request $request)
    {
        $coupon_code = $request->coupon_code;
        $user_id = Auth::id();
        $items = CartItem::where('user_id', $user_id)->get();
        $subtotal = $items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        if (isset($coupon_code)) {
            $coupon = Coupon::where('code', $coupon_code)->where('expiry_date', '>=', Carbon::today())->where('cart_value', '<=', $subtotal)->first();
            if (!$coupon) {
                return redirect()->back()->with('error', 'Voucher tidak valid!');
            } else {
                Session::put('coupon', [
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'cart_value' => $coupon->cart_value
                ]);
                $this->calculateDiscount();
                return redirect()->back()->with('success', 'Voucher Berhasil digunakan!');
            }
        } else {
            return redirect()->back()->with('error', 'Voucher tidak valid!');
        }
    }

    public function calculateDiscount()
    {
        $user_id = Auth::id();
        $items = CartItem::where('user_id', $user_id)->get();
        $subtotal = $items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $discount = 0;
        if (Session::has('coupon')) {
            if (Session::get('coupon')['type'] == 'fixed') {
                $discount = Session::get('coupon')['value'];
            } else {
                $discount = ($subtotal * Session::get('coupon')['value']) / 100;
            }


            $subtotalAfterDiscount = $subtotal - $discount;
            $taxAfterDiscount = ($subtotalAfterDiscount * 10) / 100; //Pajak 10 % 
            $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;

            Session::put('discounts', [
                'discount' => number_format(floatval($discount), 2, '.', ''),
                'subtotal' => number_format(floatval($subtotalAfterDiscount), 2, '.', ''),
                'tax' => number_format(floatval($taxAfterDiscount), 2, '.', ''),
                'total' => number_format(floatval($totalAfterDiscount), 2, '.', '')
            ]);
        }
    }

    public function remove_coupon_code()
    {
        Session::forget('coupon');
        Session::forget('discounts');
        return back()->with('success', 'Voucher berhasil dihapus!');
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();

        // Ambil alamat default user
        $address = Address::where('user_id', $user->id)->where('isdefault', 1)->first();

        // Ambil item cart user
        $items = CartItem::where('user_id', $user->id)->get();

        // Hitung subtotal awal
        $subtotal = $items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // Jika user memasukkan kode Voucher baru
        $coupon_code = $request->coupon_code;
        if ($coupon_code) {
            $coupon = Coupon::where('code', $coupon_code)
                ->where('expiry_date', '>=', Carbon::today())
                ->where('cart_value', '<=', $subtotal)
                ->first();

            if (!$coupon) {
                return redirect()->back()->with('error', 'Kode Voucher tidak valid atau tidak memenuhi syarat.');
            }

            // Simpan Voucher ke session
            Session::put('coupon', [
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'cart_value' => $coupon->cart_value
            ]);

            // Hitung diskon dan simpan ke session
            $this->calculateDiscount();
        }

        // Ambil data diskon jika ada
        $discountData = null;
        if (Session::has('discounts')) {
            $discount = Session::get('discounts')['discount'];
            $subtotalAfterDiscount = Session::get('discounts')['subtotal'];
            $taxAfterDiscount = Session::get('discounts')['tax'];
            $totalAfterDiscount = Session::get('discounts')['total'];
            $code = Session::get('coupon')['code'];

            $discountData = [
                'code' => $code,
                'discount' => $discount,
                'subtotal' => $subtotalAfterDiscount,
                'tax' => $taxAfterDiscount,
                'total' => $totalAfterDiscount,
            ];

            // Gunakan data setelah diskon
            $subtotal = $subtotalAfterDiscount;
            $tax = $taxAfterDiscount;
            $total = $totalAfterDiscount;
        } else {
            // Tanpa diskon
            $taxRate = 0.10;
            $tax = $subtotal * $taxRate;
            $total = $subtotal + $tax;
        }

        return view('checkout', compact(
            'address',
            'items',
            'subtotal',
            'tax',
            'total',
            'discountData'
        ));
    }


    public function place_an_order(Request $request)
    {
        $user_id = Auth::id();
        $address = Address::where('user_id', $user_id)->where('isdefault', true)->first();

        $request->validate(
            [
                'mode' => 'required|in:card,paypal,cod'
            ],
            [
                'mode.required' => 'Silakan pilih metode pembayaran.'
            ]
        );

        if (!$address) {
            $request->validate([
                'name' => 'required|max:100',
                'phone' => 'required|numeric|digits_between:10,13',
                'zip' => 'required|numeric|digits:5',
                'state' => 'required',
                'city' => 'required',
                'address' => 'required',
                'locality' => 'required',
                'landmark' => 'required',
            ]);
            $address = new Address();
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->zip = $request->zip;
            $address->state = $request->state;
            $address->city = $request->city;
            $address->address = $request->address;
            $address->locality = $request->locality;
            $address->landmark = $request->landmark;
            $address->country = 'Indonesia';
            $address->user_id = $user_id;
            $address->isdefault = true;
            $address->save();
        }

        // Hitung jumlah untuk checkout dari database
        $this->setAmountForCheckout();

        $checkout = Session::get('checkout');

        $order = new Order();
        $order->user_id = $user_id;
        $order->subtotal = $checkout['subtotal'];
        $order->discount = $checkout['discount'];
        $order->tax = $checkout['tax'];
        $order->total = $checkout['total'];
        $order->name = $address->name;
        $order->phone = $address->phone;
        $order->locality = $address->locality;
        $order->address = $address->address;
        $order->city = $address->city;
        $order->state = $address->state;
        $order->country = $address->country;
        $order->landmark = $address->landmark;
        $order->zip = $address->zip;
        $order->save();

        // Ambil semua CartItem milik user untuk dimasukkan ke OrderItem
        $cartItems = CartItem::where('user_id', $user_id)->get();

        foreach ($cartItems as $item) {
            $orderItem = new OrderItem();
            $orderItem->product_id = $item->product_id;
            $orderItem->order_id = $order->id;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->quantity;
            $orderItem->save();

            // --- [LOGIKA PENGURANGAN STOK DITAMBAHKAN DI SINI] ---
            // Cari produk berdasarkan ID dari item keranjang
            $product = Product::find($item->product_id);
            if ($product) {
                // Kurangi kuantitas produk dengan kuantitas yang dibeli
                $product->quantity -= $item->quantity;
                // Simpan perubahan pada produk
                $product->save();
            }
            // --- [AKHIR DARI LOGIKA PENGURANGAN STOK] ---
        }


        // Simpan transaksi
        if ($request->mode == "card") {
            // proses untuk pembayaran kartu (bisa ditambahkan)
        } elseif ($request->mode == "paypal") {
            // proses untuk paypal (bisa ditambahkan)
        } elseif ($request->mode == "cod") {
            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order->id;
            $transaction->mode = $request->mode;
            $transaction->status = "pending";
            $transaction->save();
        }

        // Hapus semua CartItem milik user setelah order berhasil
        CartItem::where('user_id', $user_id)->delete();

        // Bersihkan session checkout, coupon, discount
        Session::forget('checkout');
        Session::forget('coupon');
        Session::forget('discounts');

        Session::put('order_id', $order->id);

        return redirect()->route('cart.order.confirmation');
    }

    public function setAmountForCheckout()
    {
        $user_id = Auth::id();

        // Ambil semua cart item milik user
        $items = CartItem::where('user_id', $user_id)->get();

        if ($items->isEmpty()) {
            Session::forget('checkout');
            return;
        }

        // Hitung subtotal (harga * qty)
        $subtotal = $items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // Hitung diskon jika ada session discount
        if (Session::has('discounts')) {
            $discountData = Session::get('discounts');
            $discount = $discountData['discount'] ?? 0;
            $subtotalAfterDiscount = $discountData['subtotal'] ?? ($subtotal - $discount);
            $tax = $discountData['tax'] ?? round($subtotalAfterDiscount * 0.1, 2);
            $total = $discountData['total'] ?? ($subtotalAfterDiscount + $tax);
        } else {
            $discount = 0;
            $subtotalAfterDiscount = $subtotal;
            $tax = round($subtotal * 0.1, 2); // misal 10% tax
            $total = $subtotal + $tax;
        }

        Session::put('checkout', [
            'discount' => $discount,
            'subtotal' => $subtotalAfterDiscount,
            'tax' => $tax,
            'total' => $total
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
