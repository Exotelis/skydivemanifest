<?php

namespace App\Http\Controllers;

use App\Http\Requests\IntIdRequest;
use App\Models\User;
use App\Models\Waiver;
use App\Traits\Paginate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class UserWaiverController
 * @package App\Http\Controllers
 */
class UserWaiverController extends Controller
{
    use Paginate;

    /**
     * Get a list of all signed waivers.
     *
     * @param Request  $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function all(Request $request, User $user)
    {
        $this->validatePagination($request->only('limit', 'page'));

        $signedWaivers = QueryBuilder::for($user->waivers()->withTimestamps())
            ->allowedFilters(\App\Filters\WaiverSignedFilters::filters())
            ->allowedSorts(\App\Filters\WaiverSignedFilters::sorting())
            ->defaultSort('id')
            ->paginate($request->input('limit'));

        return response()->json($signedWaivers);
    }

    /**
     * Delete a single signed waiver.
     *
     * @param  Request $request
     * @param  User    $user
     * @param  Waiver  $waiver
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, User $user, Waiver $waiver)
    {
        try {
            $user->waivers()->detach($waiver->id);
            $executedBy = currentUserLogString() ?? 'system';
            Log::info("[User-Waiver] Signed waivers '{$waiver->logString()}' of user '{$user->logString()}' " .
                "has been deleted by '{$executedBy}'");
        } catch (\Exception $e) {
            abort(500, __('error.500'));
        }

        return response()->json(['message' => trans_choice('messages.deleted_waivers_signed', 1)]);
    }

    /**
     * Delete one or more signed waivers of a specific user.
     *
     * @param IntIdRequest $request
     * @param User         $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBulk(IntIdRequest $request, User $user)
    {
        $input = $request->only(['ids']);
        $count = 0;

        try {
            $count = $user->waivers()->detach($input['ids']);
            $executedBy = currentUserLogString() ?? 'system';
            Log::info("[User-Waiver] '{$count}' signed waivers of user '{$user->logString()}' have been deleted " .
                "by '{$executedBy}'");
        } catch (\Exception $e) {
            abort(500, __('error.500'));
        }

        return response()->json([
            'count'   => $count,
            'message' => trans_choice('messages.deleted_waivers_signed', $count)
        ]);
    }

    /**
     * Return a single signed waiver.
     *
     * @param  Request $request
     * @param  User    $user
     * @param  Waiver  $waiver
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, User $user, Waiver $waiver)
    {
        return response()->json($waiver->load(['texts' => function ($q) {
            $q->whereLanguageCode(App::getLocale())->orderBy('position');
        }]));
    }

    /**
     *
     * Me / Personal methods
     *
     */

    /**
     * Return the signed waivers of the current user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function meAll(Request $request)
    {
        return $this->all($request, $request->user());
    }

    /**
     * Get a single signed waiver of the current user.
     *
     * @param Request $request
     * @param Waiver  $waiver
     * @return \Illuminate\Http\JsonResponse
     */
    public function meGet(Request $request, Waiver $waiver)
    {
        return $this->get($request, $request->user(), $waiver);
    }
}
