<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {

        DB::beginTransaction();

        try {

            $customers = Customer::sortingQuery()
                ->searchQuery()
                ->paginationQuery();

            DB::commit();

            return $this->success('customers retrived successfully', $customers);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function store(CustomerStoreRequest $request)
    {
        DB::beginTransaction();
        $payload = collect($request->validated());

        try {

            $customer = Customer::create($payload->toArray());

            DB::commit();

            return $this->success('customer created successfully', $customer);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {

            $customer = Customer::findOrFail($id);
            DB::commit();

            return $this->success('customer retrived successfully by id', $customer);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function update(CustomerUpdateRequest $request, $id)
    {
        DB::beginTransaction();

        $payload = collect($request->validated());

        try {

            $customer = Customer::findOrFail($id);
            $customer->update($payload->toArray());
            DB::commit();

            return $this->success('customer updated successfully by id', $customer);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $customer = Customer::findOrFail($id);
            $customer->delete($id);

            DB::commit();

            return $this->success('customer deleted successfully by id', []);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }
}
