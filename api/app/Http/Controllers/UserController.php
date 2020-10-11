<?php

namespace App\Http\Controllers;

use App\Http\Requests\IntIdRequest;
use App\Http\Requests\User\BulkDeleteRequest;
use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Role;
use App\Models\User;
use App\Traits\Paginate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
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

        $users = QueryBuilder::for(User::class)
            ->with('role')
            ->allowedFilters(\App\Filters\UserFilters::filters())
            ->allowedSorts(\App\Filters\UserFilters::sorting())
            ->defaultSort('lastname', 'firstname')
            ->paginate($request->input('limit'));

        return response()->json($users);
    }

    /**
     * Create a new user.
     *
     * @param CreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateRequest $request)
    {
        $input = $request->only([
            'dob',
            'email',
            'firstname',
            'gender',
            'is_active',
            'lastname',
            'locale',
            'middlename',
            'mobile',
            'password_change',
            'phone',
            'username',
            'timezone',
        ]);
        $user = null;

        try {
            DB::beginTransaction();

            $user = User::create($input);
            $role = Role::findOrFail($request->input('role') ?? defaultRole());
            $user->role()->associate($role)->save();

            DB::commit();
        } catch (\Exception $exception) {
            abort(500, __('messages.user_created_failed'));
        }

        return response()->json(['message' => __('messages.user_created'), 'data' => $user], 201);
    }


    /**
     * Mark a single user as deleted.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $id)
    {
        $user = User::find($id);

        if (\is_null($user)) {
            abort(404, __('error.404'));
        }

        // When trying to delete the last existing admin, it should fail.
        $admins = User::where('role_id', '=', adminRole())->get();
        if ($admins->count() <= 1 && $admins->first()->id === (int)$id) {
            abort(400, __('error.user_not_deletable_last_admin'));
        }

        try {
            $user->delete();
        } catch (\Exception $e) {
            abort(500, __('error.500'));
        }

        return response()->json(['message' => trans_choice('messages.deleted_users', 1)]);
    }

    /**
     * Delete one or more users.
     *
     * @param BulkDeleteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBulk(BulkDeleteRequest $request)
    {
        $input = $request->only(['ids']);

        $count = User::destroy($input['ids']);;
        return response()->json([
            'count'   => $count,
            'message' => trans_choice('messages.deleted_users', $count)
        ]);
    }

    /**
     * Delete one or more users permanently.
     *
     * @param BulkDeleteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePermanently(BulkDeleteRequest $request)
    {
        $input = $request->only(['ids']);
        $count = 0;

        foreach ($input['ids'] as $id) {
            $user = User::onlyTrashed()->find((int) $id);

            if (\is_null($user)) {
                continue;
            }

            if ($user->forceDelete()) {
                $count++;
            }
        }

        return response()->json([
            'count'   => $count,
            'message' => trans_choice('messages.deleted_users_permanently', $count)
        ]);
    }

    /**
     * Restore a deleted user.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(Request $request, $id)
    {
        $user = User::withTrashed()->find($id);

        if (\is_null($user)) {
            abort(404, __('error.404'));
        }

        if (! $user->trashed()) {
            abort(400, __('error.user_not_deleted'));
        }

        if(! $user->restore()) {
            abort(500, __('error.500'));
        }

        return response()->json(['message' => trans_choice('messages.deleted_users_restored', 1)]);
    }

    /**
     * Restore soft deleted users.
     *
     * @param IntIdRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function restoreBulk(IntIdRequest $request)
    {
        $input = $request->only(['ids']);
        $count = 0;

        foreach ($input['ids'] as $id) {
            $user = User::onlyTrashed()->find((int) $id);

            if (\is_null($user)) {
                continue;
            }

            if ($user->restore()) {
                $count++;
            }
        }

        return response()->json([
            'count'   => $count,
            'message' => trans_choice('messages.deleted_users_restored', $count)
        ]);
    }

    /**
     * Get a list of soft deleted users.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function trashed(Request $request)
    {
        $this->validatePagination($request->only('limit', 'page'));

        $users = QueryBuilder::for(User::class)
            ->with('role')
            ->onlyTrashed()
            ->allowedFilters(\App\Filters\UserFilters::filters())
            ->allowedSorts(\App\Filters\UserFilters::sorting())
            ->defaultSort('lastname', 'firstname')
            ->paginate($request->input('limit'));

        return response()->json($users);
    }

    /**
     * Update a user.
     *
     * @param UpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, $id)
    {
        $input = $request->only([
            'dob',
            'email',
            'firstname',
            'gender',
            'is_active',
            'lastname',
            'locale',
            'middlename',
            'mobile',
            'password_change',
            'phone',
            'username',
            'timezone',
        ]);

        $user = User::with('role')->find($id);
        $role = Role::find($request->input('role'));

        if (\is_null($user)) {
            abort(404, __('error.404'));
        }

        try {
            DB::beginTransaction();

            // Check if email change has been requested
            if (isset($input['email']) && $input['email'] !== $user->email) {
                $user->sendEmailVerificationNotification($input['email']);
                unset($input['email']);
            }

            if (! $user->update($input)) {
                throw new \Exception();
            }

            // Update role if was changed
            if (! \is_null($role)) {
                $user->role()->associate($role)->save();
            }

            DB::commit();
        } catch (\Throwable $exception) {
            abort(500, __('error.500'));
        }

        return response()->json($user);
    }

    /**
     * Return a single user.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request, $id)
    {
        $user = User::with('role')->find($id);

        if (\is_null($user)) {
            abort(404, __('error.404'));
        }

        return response()->json($user);
    }

    /**
     *
     * Me / Personal methods
     *
     */

    /**
     * Delete the current user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function meDelete(Request $request)
    {
        return $this->delete($request, $request->user()->id);
    }

    /**
     * Return the current user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function meUser(Request $request)
    {
        return $this->user($request, $request->user()->id);
    }

    /**
     * Update the current user.
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function meUpdate(UpdateRequest $request)
    {
        return $this->update($request, $request->user()->id);
    }
}
