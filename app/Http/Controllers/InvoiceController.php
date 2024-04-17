<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceStoreRequest;
use App\Http\Requests\InvoiceUpdateRequest;
use App\Models\Invoice;
use App\Exports\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {

        DB::beginTransaction();

        try {

            $invoices = Invoice::with([
                'customer',
                'orders.orderItems',
                'orders.orderItems.user',
                'orders.orderItems.product',
                'orders.tableNumber',
            ])
                ->sortingQuery()
                ->searchQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('invoices retrived successfully', $invoices);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function store(InvoiceStoreRequest $request)
    {
        DB::beginTransaction();

        $prefix = 'CP1-';
        $timestamp = now()->timestamp;
        $hashedValue = hash('crc32b', $timestamp);
        $payload = collect($request->validated());
        $payload['invoice_number'] = $prefix.$hashedValue;

        try {

            $invoice = Invoice::create($payload->toArray());
            DB::commit();

            return $this->success('invoice created successfully', $invoice);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {

            $invoice = Invoice::with([
                'customer',
                'orders.orderItems',
                'orders.orderItems.user',
                'orders.orderItems.product',
                'orders.tableNumber',
            ])->findOrFail($id);
            DB::commit();

            return $this->success('invoice retrived successfully by id', $invoice);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function update(InvoiceUpdateRequest $request, $id)
    {
        DB::beginTransaction();

        $payload = collect($request->validated());

        try {

            $invoice = Invoice::findOrFail($id);
            $invoice->update($payload->toArray());
            DB::commit();

            return $this->success('invoice updated successfully by id', $invoice);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $invoice = Invoice::findOrFail($id);
            $invoice->delete($id);

            DB::commit();

            return $this->success('invoice deleted successfully by id', []);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function export()
    {
        return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }

    // public function exportExcel(Request $request)
    // {
    //     $payload = collect($request);
    //     $columns = $payload['columns'];
    //     $columns = explode(',', $columns);

    //     return Excel::download(new InvoiceExport($columns), 'Invoices.xlsx');
    // }
}
