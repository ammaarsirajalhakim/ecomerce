<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Models\Contact;

class BaseController extends Controller
{
    public function __construct()
    {
        // Jalankan query untuk mengambil data yang dibutuhkan di semua halaman admin
        $dashboardDatas = DB::select("
            SELECT SUM(IF(status='ordered', 1, 0)) AS TotalOrdered 
            FROM Orders
        ");
        // Bagikan variabel $dashboardDatas ke SEMUA view
        View::share('dashboardDatas', $dashboardDatas);

        $totalContacts = Contact::count();
        View::share('totalContacts', $totalContacts);
    }
}
