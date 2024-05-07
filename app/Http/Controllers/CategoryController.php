<?php

namespace App\Http\Controllers;

use App\Enums\GeneralStatusEnum;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportShop;
use App\Exports\ExportShopParams;
use App\Imports\ImportShop;
use Barryvdh\DomPDF\Facade\Pdf;

class CategoryController extends Controller
{

    public function index(Request $request)
    {

        DB::beginTransaction();

        try {

            $shopId = $request->input('shop_id');

            $categories = Category::with(['items', 'items.itemData' => function ($query) use ($shopId) {
                // Apply condition based on shop_id
                if ($shopId) {
                    $query->where('shop_id', $shopId);
                }
            }])
                ->sortingQuery()
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

    public function store(CategoryStoreRequest $request)
    {
        DB::beginTransaction();
        $payload = collect($request->validated());

        try {

            $category = Category::create($payload->toArray());

            DB::commit();

            return $this->success('category created successfully', $category);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {

            $category = Category::findOrFail($id);
            DB::commit();

            return $this->success('category retrived successfully by id', $category);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function update(CategoryUpdateRequest $request, $id)
    {
        DB::beginTransaction();

        $payload = collect($request->validated());

        try {

            $category = Category::findOrFail($id);
            $category->update($payload->toArray());
            DB::commit();

            return $this->success('category updated successfully by id', $category);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $category = Category::findOrFail($id);
            $category->forceDelete();

            DB::commit();

            return $this->success('category deleted successfully by id', []);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function exportexcel()
    {
        return Excel::download(new ExportShop, 'Shops.xlsx');
    }

    public function exportparams(Request $request)
    {
        $filters = [
            'page' => $request->input('page'),
            'per_page' => $request->input('per_page'),
            'columns' => $request->input('columns'),
            'search' => $request->input('search'),
            'order' => $request->input('order'),
            'sort' => $request->input('sort'),
            'value' => $request->input('value'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];
        return Excel::download(new ExportShopParams($filters), 'Shops.xlsx');
    }

    public function exportpdf()
    {
        $data = Shop::all();
        $pdf = Pdf::loadView('shopexport', ['data' => $data]);
        return $pdf->download();
    }

    public function exportpdfparams()
    {
        $data = Shop::searchQuery()
        ->sortingQuery()
        ->filterQuery()
        ->filterDateQuery()
        ->paginationQuery();
        
        $pdf = Pdf::loadView('shopexport', ['data' => $data]);
        return $pdf->download();
    }

    public function import()
    {
        Excel::import(new ImportShop, request()->file('file'));

        return $this->success('Shop is imported successfully');
    }
}
