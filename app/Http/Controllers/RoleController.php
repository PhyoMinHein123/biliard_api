<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(Request $request)
    {

        DB::beginTransaction();

        try {

            $roles = Role::with(['permissions'])->sortingQuery()
                ->searchQuery()
                ->paginationQuery();

            DB::commit();

            return $this->success('roles retrived successfully', $roles);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function store(RoleStoreRequest $request)
    {
        DB::beginTransaction();
        $payload = collect($request->validated());

        try {

            $role = Role::create($payload->toArray());

            DB::commit();

            return $this->success('role created successfully', $role);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {

            $role = Role::with(['permissions'])->findOrFail($id);
            DB::commit();

            return $this->success('role retrived successfully by id', $role);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function update(RoleUpdateRequest $request, $id)
    {
        DB::beginTransaction();

        $payload = collect($request->validated());

        $payloadUpdate = [
            'name' => $payload['name'],
            'description' => $payload['description'],
        ];

        try {

            $role = Role::with(['permissions'])->findOrFail($id);
            $role->update($payloadUpdate);

            /*** get permission from database */
            $getPermissions = $role->toArray()['permissions'];

            $oldPermissions = collect($getPermissions)->map(function ($permission) {
                return $permission['id'];
            });

            $role->revokePermissionTo($oldPermissions);

            if (isset($payload['permissions'])) {
                $currentRole = Role::findById($id);
                $permissionIds = $payload['permissions'];

                foreach ($permissionIds as $permissionId) {
                    $permission = Permission::findById($permissionId);
                    $currentRole->givePermissionTo($permission['name']);
                }

            }

            DB::commit();

            return $this->success('role updated successfully by id', $role);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $role = Role::with(['permissions'])->findOrFail($id);

            $getPermissions = $role->toArray()['permissions'];

            $oldPermissions = collect($getPermissions)->map(function ($permission) {
                return $permission['id'];
            });

            $role->revokePermissionTo($oldPermissions);

            $role->delete($id);

            DB::commit();

            return $this->success('role deleted successfully by id', []);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }
}
