<?php

namespace App\Http\Controllers;

use App\Enums\GeneralStatusEnum;
use App\Http\Requests\CashierStoreRequest;
use App\Http\Requests\CashierUpdateRequest;
use App\Models\Cashier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    public function index(Request $request)
    {

        DB::beginTransaction();

        try {

            $categories = Cashier::sortingQuery()
                ->searchQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            DB::commit();

            return $this->success('categories retrived successfully', $categories);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function store(CashierStoreRequest $request)
    {
        DB::beginTransaction();
        $payload = collect($request->validated());
        $payload['status'] = GeneralStatusEnum::ACTIVE->value;

        try {

            $cashier = Cashier::create($payload->toArray());

            DB::commit();

            return $this->success('cashier created successfully', $cashier);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {

            $cashier = Cashier::findOrFail($id);
            DB::commit();

            return $this->success('cashier retrived successfully by id', $cashier);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function update(CashierUpdateRequest $request, $id)
    {
        DB::beginTransaction();

        $payload = collect($request->validated());

        try {

            $cashier = Cashier::findOrFail($id);
            $cashier->update($payload->toArray());
            DB::commit();

            return $this->success('cashier updated successfully by id', $cashier);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $cashier = Cashier::findOrFail($id);
            $cashier->delete($id);

            DB::commit();

            return $this->success('cashier deleted successfully by id', []);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }
}
