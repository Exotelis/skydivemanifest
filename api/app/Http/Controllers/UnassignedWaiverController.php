<?php

namespace App\Http\Controllers;

use App\Http\Requests\IntIdRequest;
use App\Http\Requests\UnassignedWaiver\AssignRequest;
use App\Models\UnassignedWaiver;
use App\Models\User;
use App\Traits\Paginate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class UnassignedWaiverController
 * @package App\Http\Controllers
 */
class UnassignedWaiverController extends Controller
{
    use Paginate;

    /**
     * Get a list of all unassigned waivers.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function all(Request $request)
    {
        $this->validatePagination($request->only('limit', 'page'));

        $unassignedWaivers = QueryBuilder::for(UnassignedWaiver::class)
            ->with(['waiver'])
            ->allowedFilters(\App\Filters\UnassignedWaiverFilters::filters())
            ->allowedSorts(\App\Filters\UnassignedWaiverFilters::sorting())
            ->defaultSort('id')
            ->paginate($request->input('limit'));

        return response()->json($unassignedWaivers);
    }

    /**
     * Assign a unassigned signed waiver to a given user.
     *
     * @param AssignRequest    $request
     * @param UnassignedWaiver $unassignedWaiver
     * @return \Illuminate\Http\JsonResponse
     */
    public function assign(AssignRequest $request, UnassignedWaiver $unassignedWaiver)
    {
        // Get user
        $user = User::find($request->input('user_id'));

        try {
            DB::beginTransaction();

            // Assign waiver and delete the unassigned
            $unassignedWaiver
                ->waiver
                ->users()
                ->syncWithoutDetaching([$user->id => ['signature' => $unassignedWaiver->signature]]);
            $unassignedWaiver->delete();

            // Log message
            $executedBy = currentUserLogString() ?? 'system';
            Log::info("[User-Waiver] The unassigned waiver '{$unassignedWaiver->logString()}' has been " .
                "assigned to user '{$user->logString()}' by '{$executedBy}'");

            DB::commit();
        } catch (\Exception $e) {
            abort(500, __('error.500'));
        }

        return response()->json(
            ['message' => __('messages.waiver_assigned', ['id' => $user->id, 'user' => $user->email])],
            201
        );
    }


    /**
     * Delete a single unassigned waiver.
     *
     * @param Request          $request
     * @param UnassignedWaiver $unassignedWaiver
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, UnassignedWaiver $unassignedWaiver)
    {
        try {
            $unassignedWaiver->delete();
        } catch (\Exception $e) {
            abort(500, __('error.500'));
        }

        return response()->json(['message' => trans_choice('messages.deleted_waivers_unassigned', 1)]);
    }

    /**
     * Delete one or more unassigned waivers.
     *
     * @param IntIdRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBulk(IntIdRequest $request)
    {
        $input = $request->only(['ids']);
        $count = UnassignedWaiver::destroy($input['ids']);

        return response()->json([
            'count'   => $count,
            'message' => trans_choice('messages.deleted_waivers_unassigned', $count)
        ]);
    }

    /**
     * Return a single unassigned waiver.
     *
     * @param Request          $request
     * @param UnassignedWaiver $unassignedWaiver
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, UnassignedWaiver $unassignedWaiver)
    {
        return response()->json($unassignedWaiver->load('waiver'));
    }
}
