<?php

namespace App\Http\Controllers;

use App\Http\Requests\TableNumberStoreRequest;
use App\Http\Requests\TableNumberUpdateRequest;
use App\Models\TableNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableNumberController extends Controller
{
    public function index(Request $request)
    {

        DB::beginTransaction();

        try {

            $tableNumbers = TableNumber::with(['orders'])
                ->sortingQuery()
                ->searchQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            DB::commit();

            return $this->success('tableNumbers retrived successfully', $tableNumbers);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function store(TableNumberStoreRequest $request)
    {
        DB::beginTransaction();
        $payload = collect($request->validated());

        try {

            $tableNumber = TableNumber::create($payload->toArray());

            DB::commit();

            return $this->success('tableNumber created successfully', $tableNumber);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {

            $tableNumber = TableNumber::findOrFail($id);
            DB::commit();

            return $this->success('tableNumber retrived successfully by id', $tableNumber);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function update(TableNumberUpdateRequest $request, $id)
    {
        DB::beginTransaction();

        $payload = collect($request->validated());

        try {

            $tableNumber = TableNumber::findOrFail($id);
            $tableNumber->update($payload->toArray());
            DB::commit();

            return $this->success('tableNumber updated successfully by id', $tableNumber);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $tableNumber = TableNumber::findOrFail($id);
            $tableNumber->delete($id);

            DB::commit();

            return $this->success('tableNumber deleted successfully by id', []);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }
}