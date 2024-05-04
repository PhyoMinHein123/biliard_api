<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Models\Order;
use App\Models\TableNumber;
use App\Enums\TableStatusEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {

        DB::beginTransaction();

        try {

            $orders = Order::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            DB::commit();

            return $this->success('orders retrived successfully', $orders);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function store(OrderStoreRequest $request)
    {
        DB::beginTransaction();
        $payload = collect($request->validated());
        $payload['checkin'] = Carbon::now('Asia/Yangon');

        try {
            
            $order = Order::create($payload->toArray());
           
            if ($request->has('table_number_id')) {
                $tableNumber = TableNumber::findOrFail($request->table_number_id);
                $tableNumber->update([
                    'status' => TableStatusEnum::PENDING,
                    'order_id' => $order->id
                ]);
            }

            DB::commit();

            return $this->success('order created successfully', $order);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {

            $order = Order::with(['orderItems', 'orderItems.product', 'orderItems.user', 'tableNumber'])->findOrFail($id);
            DB::commit();

            return $this->success('order retrived successfully by id', $order);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function update(OrderUpdateRequest $request, $id)
    {
        DB::beginTransaction();

        $payload = collect($request->validated());

        try {

            $order = Order::findOrFail($id);
            $order->update($payload->toArray());
            DB::commit();

            return $this->success('order updated successfully by id', $order);

        } catch (Exception $e) {

            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $order = Order::findOrFail($id);
            $order->delete($id);

            DB::commit();

            return $this->success('order deleted successfully by id', []);

        } catch (Exception $e) {

            DB::rollback();

            return $this->internalServerError();
        }
    }
}
