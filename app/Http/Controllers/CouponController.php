<?php
// app/Http/Controllers/CouponController.php
namespace App\Http\Controllers;

use App\Models\Coupon;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function publicIndex()
    {
        $now = Carbon::now()->startOfDay();

        // Tampilkan kupon yang belum kedaluwarsa (atau tidak ada expiry_date)
        $rows = Coupon::query()
            ->where(function($q) use ($now) {
                $q->whereNull('expiry_date')->orWhereDate('expiry_date', '>=', $now);
            })
            ->orderBy('expiry_date', 'asc')
            ->get();

        // Normalisasi data untuk view
        $coupons = $rows->map(function($c) use ($now) {
            $end = $c->expiry_date ? Carbon::parse($c->expiry_date)->endOfDay() : null;
            return [
                'code'         => $c->code,
                'type'         => $c->type,                 // 'percent' | 'fixed'
                'value'        => (float)$c->value,
                'min_order'    => $c->cart_value,           // dipakai sebagai minimal belanja
                'max_discount' => null,                     // tidak ada di skema admin, biarkan null
                'end'          => $end?->toDateString(),
                'is_active'    => is_null($end) || $end->gte($now),
                'left_days'    => $end ? $now->diffInDays($end, false) : null,
            ];
        });

        return view('kupon', compact('coupons'));
    }
}

