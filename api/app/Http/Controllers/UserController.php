<?php

namespace App\Http\Controllers;

use App\Http\Requests\IntIdRequest;
use App\Http\Requests\User\BulkDeleteRequest;
use App\Models\User;
use App\Traits\Paginate;
use Illuminate\Http\Request;
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
     * Delete one or more users.
     *
     * @param BulkDeleteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkDelete(BulkDeleteRequest $request)
    {
        $input = $request->only(['ids']);
        $count = User::destroy($input['ids']);
        return response()->json([
            'count'   => $count,
            'message' => trans_choice('messages.deleted_users', $count)
        ]);
    }

    public function create()
    {

    }

    public function delete()
    {

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

            if( is_null($user)) {
                continue;
            }

            $user->forceDelete();
            $count++;
        }

        return response()->json([
            'count'   => $count,
            'message' => trans_choice('messages.deleted_users_permanently', $count)
        ]);
    }

    /**
     * Restore soft deleted user.
     *
     * @param IntIdRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(IntIdRequest $request)
    {
        $input = $request->only(['ids']);
        $count = 0;

        foreach ($input['ids'] as $id) {
            $user = User::onlyTrashed()->find((int) $id);

            if( is_null($user)) {
                continue;
            }

            $user->restore();
            $count++;
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
}
