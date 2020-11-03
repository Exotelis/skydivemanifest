<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\CreateRequest;
use App\Http\Requests\Role\DeleteRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Traits\Paginate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class RoleController
 * @package App\Http\Controllers
 */
class RoleController extends Controller
{
    use Paginate;

    /**
     * Get a list of all users.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function all(Request $request)
    {
        $this->validatePagination($request->only('limit', 'page'));

        $users = QueryBuilder::for(Role::class)
            ->allowedFilters(\App\Filters\RoleFilters::filters())
            ->allowedSorts(\App\Filters\RoleFilters::sorting())
            ->defaultSort('name')
            ->paginate($request->input('limit'));

        return response()->json($users);
    }

    /**
     * Create a new user role.
     *
     * @param CreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateRequest $request)
    {
        $role = null;

        try {
            DB::beginTransaction();

            $role = Role::create($request->validated());
            $defaultPermissions = Permission::where('is_default', '=', true)
                ->get()
                ->pluck('slug')
                ->all();

            // Add default permissions
            $permissions = \array_unique(\array_merge($request->permissions ?? [], $defaultPermissions));

            $role->permissions()->attach($permissions);

            DB::commit();
        } catch (\Exception $exception) {
            abort(500, __('messages.role_created_failed'));
        }

        return response()->json(['message' => __('messages.role_created'), 'data' => $role], 201);
    }

    /**
     * Delete a single role.
     *
     * @param Request $request
     * @param Role    $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, Role $role)
    {
        if (! $role->deletable) {
            abort(400, __('error.role_not_deletable'));
        }

        if (\count($role->users) > 0) {
            abort(400, __('error.role_in_use'));
        }

        try {
            $role->delete();
        } catch (\Exception $e) {
            abort(500, __('error.500'));
        }

        return response()->json(['message' => trans_choice('messages.deleted_roles', 1)]);
    }

    /**
     * Delete one or more roles.
     *
     * @param DeleteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBulk(DeleteRequest $request)
    {
        $input = $request->only(['ids']);
        $count = Role::destroy($input['ids']);

        return response()->json([
            'count'   => $count,
            'message' => trans_choice('messages.deleted_roles', $count)
        ]);
    }

    /**
     * Return a single role.
     *
     * @param Request $request
     * @param Role    $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function role(Request $request, Role $role)
    {
        return response()->json($role->load('permissions'));
    }

    /**
     * Update a role.
     *
     * @param UpdateRequest $request
     * @param Role          $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Role $role)
    {
        $input = $request->only([
            'color',
            'name',
            'permissions',
        ]);

        if (isset($input['permissions']) && !$role->editable) {
            abort(400, __('error.role_not_editable'));
        }

        try {
            DB::beginTransaction();

            $role->name = $input['name'] ?? $role->name;
            $role->color = $input['color'] ?? $role->color;

            $defaultPermissions = Permission::where('is_default', '=', true)
                ->get()
                ->pluck('slug')
                ->all();

            // Add default permissions
            $permissions = \array_unique(\array_merge($input['permissions'] ?? [], $defaultPermissions));

            $role->permissions()->sync(
                isset($input['permissions']) ? $permissions : $role->permissions->pluck('slug')->toArray()
            );

            $role->saveOrFail();

            DB::commit();
        } catch (\Throwable $exception) {
            abort(500, __('error.500'));
        }

        $role->refresh();
        return response()->json($role->load('permissions'));
    }
}
