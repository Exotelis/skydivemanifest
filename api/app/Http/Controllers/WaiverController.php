<?php

namespace App\Http\Controllers;

use App\Http\Requests\IntIdRequest;
use App\Http\Requests\Waiver\CreateRequest;
use App\Http\Requests\Waiver\UpdateRequest;
use App\Models\Waiver;
use App\Traits\Paginate;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class WaiverController
 * @package App\Http\Controllers
 */
class WaiverController extends Controller
{
    use Paginate;

    /**
     * Get active waivers.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function active(Request $request)
    {
        $this->validatePagination($request->only('limit', 'page'));

        $qualifications = QueryBuilder::for(
                Waiver::with(['texts' => function ($q) { $q->orderBy('position'); }])->whereIsActive(true)
            )
            ->allowedFilters(\App\Filters\WaiverActiveFilters::filters())
            ->allowedSorts(\App\Filters\WaiverActiveFilters::sorting())
            ->defaultSort('id')
            ->paginate($request->input('limit'));

        return response()->json($qualifications);
    }

    /**
     * Get a list of all waivers.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function all(Request $request)
    {
        $this->validatePagination($request->only('limit', 'page'));

        $qualifications = QueryBuilder::for(Waiver::class)
            ->allowedFilters(\App\Filters\WaiverFilters::filters())
            ->allowedSorts(\App\Filters\WaiverFilters::sorting())
            ->defaultSort('id')
            ->paginate($request->input('limit'));

        return response()->json($qualifications);
    }

    /**
     * Create a new waiver.
     *
     * @param CreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateRequest $request)
    {
        $waiver = null;

        try {
            $waiver = Waiver::create($request->validated());
        } catch (\Throwable $exception) {
            abort(500, __('messages.waiver_created_failed'));
        }

        return response()->json(['message' => __('messages.waiver_created'), 'data' => $waiver], 201);
    }

    /**
     * Delete a single waiver.
     *
     * @param Request $request
     * @param Waiver  $waiver
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, Waiver $waiver)
    {
        try {
            $waiver->delete();
        } catch (\Exception $e) {
            abort(500, __('error.500'));
        }

        return response()->json(['message' => trans_choice('messages.deleted_waivers', 1)]);
    }

    /**
     * Delete one or more waivers.
     *
     * @param IntIdRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBulk(IntIdRequest $request)
    {
        $input = $request->only(['ids']);
        $count = Waiver::destroy($input['ids']);

        return response()->json([
            'count'   => $count,
            'message' => trans_choice('messages.deleted_waivers', $count)
        ]);
    }

    /**
     * Return a single waiver.
     *
     * @param Request $request
     * @param Waiver  $waiver
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, Waiver $waiver)
    {
        return response()->json($waiver->load('texts'));
    }

    /**
     * Update a waiver.
     *
     * @param UpdateRequest $request
     * @param Waiver        $waiver
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Waiver $waiver)
    {
        try {
            $waiver->update($request->validated());
        } catch (\Throwable $exception) {
            abort(500, __('error.500'));
        }

        return response()->json($waiver);
    }
}
