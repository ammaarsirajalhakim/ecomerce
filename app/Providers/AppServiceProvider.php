<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Fasade untuk View
use Illuminate\Support\Facades\Auth;  // Fasade untuk Autentikasi
use App\Models\Wishlist;              // Model untuk Wishlist
use App\Models\About;                 // Model untuk Profil Usaha
use App\Models\Category;              // Model untuk Kategori

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
        // Menggunakan View Composer untuk membagikan data ke layout 'layouts.app'
        // Kode ini akan berjalan setiap kali sebuah halaman frontend dirender.
        View::composer('layouts.app', function ($view) {
            
            // Fungsionalitas Wishlist (TETAP ADA, TIDAK DIUBAH)
            $wishlistCount = 0;
            if (Auth::check()) {
                $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
            }
            $view->with('wishlistCount', $wishlistCount);


            // --- DATA TAMBAHAN UNTUK PROFIL USAHA & FOOTER ---

            // 1. Mengambil data profil usaha (logo & banner)
            $about_us_data = About::first();
            
            // 2. Mengambil kategori untuk ditampilkan di footer
            $footerCategories = Category::orderBy('name')->take(5)->get();

            // 3. Membagikan variabel tambahan ke view
            $view->with('about_us_data', $about_us_data);
            $view->with('footerCategories', $footerCategories);

        });
    }
}