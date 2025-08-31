<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Import View facade
use Illuminate\Support\Facades\Auth; // Import Auth facade
use App\Models\Wishlist;             // Import Wishlist model

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ======================= TAMBAHKAN KODE INI =======================
        // Menggunakan View Composer untuk membagikan data ke view 'layouts.app'
        // Kode ini akan berjalan setiap kali sebuah halaman yang menggunakan layout utama di-render.
        View::composer('layouts.app', function ($view) {
            $wishlistCount = 0;
            // Hanya hitung jika user sudah login
            if (Auth::check()) {
                $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
            }
            // Kirim variabel $wishlistCount ke view tersebut
            $view->with('wishlistCount', $wishlistCount);
        });
        // ===================== AKHIR DARI KODE TAMBAHAN =====================
    }
}

