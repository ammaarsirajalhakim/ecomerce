<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsReportExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Gunakan Query BARU yang sama untuk Excel
        return DB::table('products')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                'products.SKU',
                'products.name',
                DB::raw('COALESCE(SUM(CASE WHEN orders.status = "delivered" THEN order_items.quantity ELSE 0 END), 0) as total_quantity_sold')
            )
            ->groupBy('products.id', 'products.SKU', 'products.name')
            ->orderByDesc('total_quantity_sold')
            ->orderBy('products.name', 'asc')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Nama kolom di file Excel
        return [
            'SKU',
            'Nama Produk',
            'Jumlah Terjual',
        ];
    }
}