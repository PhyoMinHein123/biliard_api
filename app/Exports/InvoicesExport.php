<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithHeadings;

class InvoicesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $invoice = Invoice::with([
            'customer',
            'orders.orderItems',
            'orders.orderItems.user',
            'orders.orderItems.product',
            'orders.tableNumber',
        ])->get();
    }

    // public function headings(): array
    // {
    //     return [
    //         'id',
    //         'invoice_number',
    //         'shop_id',
    //         'customer_id',
    //         'order_id',
    //         'subtotal',
    //         'tax',
    //         'discount',
    //         'total',
    //         'payment',
    //         'charge',
    //         'refund',
    //         'checkin',
    //     ];
    // }
}
