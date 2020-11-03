<?php

namespace App\Http\Controllers;

use App\Http\Requests\Region\CreateRequest;
use App\Http\Requests\Region\UpdateRequest;
use App\Http\Requests\IntIdRequest;
use App\Models\Country;
use App\Models\Region;
use App\Traits\Paginate;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class RegionController
 * @package App\Http\Controllers
 */
class RegionController extends Controller
{
    use Paginate;

    /**
     * Get a list of all regions of a country.
     *
     * @param Request $request
     * @param Country $country
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function all(Request $request, Country $country)
    {
        $this->validatePagination($request->only('limit', 'page'));

        $regions = QueryBuilder::for($country->regions())
            ->with(['country'])
            ->allowedFilters(\App\Filters\RegionFilters::filters())
            ->allowedSorts(\App\Filters\RegionFilters::sorting())
            ->defaultSort('id')
            ->paginate($request->input('limit'));

        return response()->json($regions);
    }

    /**
     * Create a new region for a specific country.
     *
     * @param CreateRequest $request
     * @param Country       $country
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateRequest $request, Country $country)
    {
        $region = null;

        try {
            $region = $country->regions()->create($request->validated())->withoutRelations();
        } catch (\Throwable $exception) {
            abort(500, __('messages.region_created_failed'));
        }

        return response()->json(['message' => __('messages.region_created'), 'data' => $region], 201);
    }

    /**
     * Delete a single region of a specific country.
     *
     * @param Request $request
     * @param Country $country
     * @param Region  $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, Country $country, Region $region)
    {
        try {
            $region->delete();
        } catch (\Exception $e) {
            abort(500, __('error.500'));
        }

        return response()->json(['message' => trans_choice('messages.deleted_regions', 1)]);
    }

    /**
     * Delete one or more regions of a specific country.
     *
     * @param IntIdRequest $request
     * @param Country      $country
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBulk(IntIdRequest $request, Country $country)
    {
        $input = $request->only(['ids']);
        $count = 0;

        foreach ($country->regions as $region) {
            if (\in_array($region->id, $input['ids']) && $region->delete()) {
                $count++;
            }
        }

        return response()->json([
            'count'   => $count,
            'message' => trans_choice('messages.deleted_regions', $count)
        ]);
    }

    /**
     * Return a single region of a specific country.
     *
     * @param Request $request
     * @param Country $country
     * @param Region  $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, Country $country, Region $region)
    {
        return response()->json($region->load('country'));
    }

    /**
     * Update a region of a specific country.
     *
     * @param UpdateRequest $request
     * @param Country       $country
     * @param Region        $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Country $country, Region $region)
    {
        if (! $region->update($request->validated())) {
            abort(500, __('error.500'));
        }

        return response()->json($region->load('country'));
    }
}
