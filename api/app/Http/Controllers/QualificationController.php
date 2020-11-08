<?php

namespace App\Http\Controllers;

use App\Http\Requests\Qualification\CreateRequest;
use App\Http\Requests\Qualification\DeleteBulkRequest;
use App\Http\Requests\Qualification\UpdateRequest;
use App\Models\Qualification;
use App\Traits\Paginate;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class QualificationController
 * @package App\Http\Controllers
 */
class QualificationController extends Controller
{
    use Paginate;

    /**
     * Get a list of all qualifications.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function all(Request $request)
    {
        $this->validatePagination($request->only('limit', 'page'));

        $qualifications = QueryBuilder::for(Qualification::class)
            ->allowedFilters(\App\Filters\QualificationFilters::filters())
            ->allowedSorts(\App\Filters\QualificationFilters::sorting())
            ->defaultSort('slug')
            ->paginate($request->input('limit'));

        return response()->json($qualifications);
    }

    /**
     * Create a new qualification.
     *
     * @param CreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateRequest $request)
    {
        $qualification = null;

        try {
            $qualification = Qualification::create($request->validated());
        } catch (\Throwable $exception) {
            abort(500, __('messages.qualification_created_failed'));
        }

        return response()->json(
            ['message' => __('messages.qualification_created'), 'data' => $qualification],
            201
        );
    }

    /**
     * Delete a single qualification.
     *
     * @param Request       $request
     * @param Qualification $qualification
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, Qualification $qualification)
    {
        try {
            $qualification->delete();
        } catch (\Exception $e) {
            abort(500, __('error.500'));
        }

        return response()->json(['message' => trans_choice('messages.deleted_qualifications', 1)]);
    }

    /**
     * Delete one or more qualifications.
     *
     * @param DeleteBulkRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBulk(DeleteBulkRequest $request)
    {
        $input = $request->only(['slugs']);

        $count = Qualification::destroy($input['slugs']);
        return response()->json([
            'count'   => $count,
            'message' => trans_choice('messages.deleted_qualifications', $count)
        ]);
    }

    /**
     * Return a single qualification.
     *
     * @param Request       $request
     * @param Qualification $qualification
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, Qualification $qualification)
    {
        return response()->json($qualification);
    }

    /**
     * Update a qualification.
     *
     * @param UpdateRequest $request
     * @param Qualification $qualification
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Qualification $qualification)
    {
        try {
            $qualification->update($request->validated());
        } catch (\Throwable $exception) {
            abort(500, __('error.500'));
        }

        return response()->json($qualification);
    }
}
