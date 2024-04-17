<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportUser;
use App\Exports\ExportUserParams;
use App\Imports\ImportUser;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    public function index(Request $request)
    {

        DB::beginTransaction();

        try {

            $users = User::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            DB::commit();

            return $this->success('users retrived successfully', $users);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function store(UserStoreRequest $request)
    {
        DB::beginTransaction();

        $payload = collect($request->validated());
       
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
            $image_url = Storage::url($path);
            $payload['image'] = $image_url;
        }
       
        try {

            $user = User::create($payload->toArray());

            DB::commit();

            return $this->success('user created successfully', $user);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {

            $user = User::with(['roles'])->findOrFail($id);
            DB::commit();

            return $this->success('user retrived successfully by id', $user);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        DB::beginTransaction();

        $payload = collect($request->validated());

        try {

            $user = User::findOrFail($id);
            $user->update($payload->toArray());
            DB::commit();

            return $this->success('user updated successfully by id', $user);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $user = User::findOrFail($id);
            $user->delete($id);

            DB::commit();

            return $this->success('user deleted successfully by id', []);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function assignRole(Request $request)
    {
        $payload = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);

        DB::beginTransaction();

        try {

            $user = User::findOrFail($payload['user_id']);
            $user->assignRole($payload['role']);
            DB::commit();

            return $this->success('role assign successfully', $user);

        } catch (Exception) {
            DB::rollBack();

            return $this->internalServerError();
        }
    }

    public function removeRole(Request $request)
    {
        $payload = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);

        DB::beginTransaction();

        try {

            $user = User::findOrFail($payload['user_id']);
            $user->removeRole($payload['role']);
            DB::commit();

            return $this->success('role remove successfully', $user);

        } catch (Exception) {
            DB::rollBack();

            return $this->internalServerError();
        }
    }

    public function exportexcel()
    {
        return Excel::download(new ExportUser, 'Shops.xlsx');
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
        return Excel::download(new ExportUserParams($filters), 'Users.xlsx');
    }

    public function exportpdf()
    {
        $data = User::all();
        $pdf = Pdf::loadView('userexport', ['data' => $data]);
        return $pdf->download();
    }

    public function exportpdfparams()
    {
        $data = User::searchQuery()
        ->sortingQuery()
        ->filterQuery()
        ->filterDateQuery()
        ->paginationQuery();
        $pdf = Pdf::loadView('userexport', ['data' => $data]);
        return $pdf->download();
    }

    public function import()
    {
        Excel::import(new ImportUser, request()->file('file'));

        return $this->success('User is imported successfully');
    }
}
