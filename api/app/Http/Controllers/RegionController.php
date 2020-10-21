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
     * @param int     $countryID
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function all(Request $request, $countryID)
    {
        $this->validatePagination($request->only('limit', 'page'));

        $country = Country::find($countryID);

        if (\is_null($country)) {
            abort(404, __('error.404'));
        }

        $addresses = QueryBuilder::for($country->regions())
            ->with(['country'])
            ->allowedFilters(\App\Filters\RegionFilters::filters())
            ->allowedSorts(\App\Filters\RegionFilters::sorting())
            ->defaultSort('id')
            ->paginate($request->input('limit'));

        return response()->json($addresses);
    }

    /**
     * Create a new region for a specific country.
     *
     * @param CreateRequest $request
     * @param int           $countryID
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateRequest $request, $countryID)
    {
        $input = $request->only([
            'region',
        ]);

        $region = null;
        $country = Country::find($countryID);

        if (\is_null($country)) {
            abort(404, __('error.404'));
        }

        try {
            $region = $country->regions()->create($input)->withoutRelations();
        } catch (\Throwable $exception) {
            abort(500, __('messages.region_created_failed'));
        }

        return response()->json(['message' => __('messages.region_created'), 'data' => $region], 201);
    }

    /**
     * Delete a single region of a specific country.
     *
     * @param Request $request
     * @param int     $countryID
     * @param int     $regionID
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $countryID, $regionID)
    {
        $country = Country::find($countryID);
        $region = Region::find($regionID);

        if (\is_null($country) || \is_null($region) || ! $country->hasRegion($region)) {
            abort(404, __('error.404'));
        }

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
     * @param int          $countryID
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBulk(IntIdRequest $request, $countryID)
    {
        $input = $request->only(['ids']);

        $country = Country::find($countryID);

        if (\is_null($country)) {
            abort(404, __('error.404'));
        }

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
     * @param int     $countryID
     * @param int     $regionID
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, $countryID, $regionID)
    {
        $country = Country::find($countryID);
        $region = Region::with('country')->find($regionID);

        if (\is_null($country) || \is_null($region) || ! $country->hasRegion($region)) {
            abort(404, __('error.404'));
        }

        return response()->json($region);
    }

    /**
     * Update a region of a specific country.
     *
     * @param UpdateRequest $request
     * @param int           $countryID
     * @param int           $regionID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, $countryID, $regionID)
    {
        $input = $request->only([
            'region',
        ]);

        $country = Country::find($countryID);
        $region = Region::find($regionID);

        if (\is_null($country) || \is_null($region) || ! $country->hasRegion($region)) {
            abort(404, __('error.404'));
        }

        if (! $region->update($input)) {
            abort(500, __('error.500'));
        }

        return response()->json($region->load('country'));
    }
}
