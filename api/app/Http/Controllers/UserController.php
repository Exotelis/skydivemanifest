<?php

namespace App\Http\Controllers;

use App\Http\Requests\IntIdRequest;
use App\Http\Requests\User\BulkDeleteRequest;
use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\QualificationsRequest;
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
        $user = null;

        try {
            DB::beginTransaction();

            $user = User::create($request->validated());
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
     * @param User    $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, User $user)
    {
        // When trying to delete the last existing admin, it should fail.
        $admins = User::where('role_id', '=', adminRole())->get();

        if ($admins->count() <= 1 && $admins->first()->id === $user->id) {
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

        $count = User::destroy($input['ids']);
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
     * Return a single user.
     *
     * @param Request $request
     * @param User    $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, User $user)
    {
        return response()->json($user->load([
            'defaultInvoice',
            'defaultInvoice.country',
            'defaultInvoice.region',
            'defaultShipping',
            'defaultShipping.country',
            'defaultShipping.region',
            'role',
        ]));
    }

    /**
     * Get all qualifications of an user.
     *
     * @param Request $request
     * @param User    $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function qualificationsGet(Request $request, User $user)
    {
        return response()->json($user->qualifications);
    }

    /**
     * Update the qualifications of an user.
     *
     * @param QualificationsRequest $request
     * @param User                  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function qualificationsUpdate(QualificationsRequest $request, User $user)
    {
        try {
            $user->qualifications()->sync($request->input('qualifications'));
        } catch (\Throwable $exception) {
            abort(500, __('messages.qualifications_updated_failed'));
        }

        return response()->json([
            'message' => __('messages.qualifications_updated'),
            'data' => $user->qualifications
        ]);
    }

    /**
     * Restore a deleted user.
     *
     * @param Request $request
     * @param User    $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(Request $request, User $user)
    {
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
     * @param User          $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, User $user)
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

        $role = Role::find($request->input('role'));

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
        return $this->delete($request, $request->user());
    }

    /**
     * Return the current user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function meGet(Request $request)
    {
        return $this->get($request, $request->user());
    }

    /**
     * Return the qualifications of the current user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function meQualificationsGet(Request $request)
    {
        return $this->qualificationsGet($request, $request->user());
    }

    /**
     * Update the qualifications of the current user.
     *
     * @param QualificationsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function meQualificationsUpdate(QualificationsRequest $request)
    {
        return $this->qualificationsUpdate($request, $request->user());
    }

    /**
     * Update the current user.
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function meUpdate(UpdateRequest $request)
    {
        return $this->update($request, $request->user());
    }
}
