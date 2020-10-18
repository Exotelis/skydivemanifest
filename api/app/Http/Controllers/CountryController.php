<?php

namespace App\Http\Controllers;

use App\Http\Requests\Country\CreateRequest;
use App\Http\Requests\Country\UpdateRequest;
use App\Http\Requests\IntIdRequest;
use App\Models\Country;
use App\Traits\Paginate;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class CountryController
 * @package App\Http\Controllers
 */
class CountryController extends Controller
{
    use Paginate;

    /**
     * Get a list of all countries.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function all(Request $request)
    {
        $this->validatePagination($request->only('limit', 'page'));

        $countries = QueryBuilder::for(Country::class)
            ->allowedFilters(\App\Filters\CountryFilters::filters())
            ->allowedSorts(\App\Filters\CountryFilters::sorting())
            ->defaultSort('id')
            ->paginate($request->input('limit'));

        return response()->json($countries);
    }

    /**
     * Create a new country.
     *
     * @param CreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateRequest $request)
    {
        $input = $request->only([
            'code',
            'country',
        ]);
        $country = null;

        try {
            $country = Country::create($input);
        } catch (\Throwable $exception) {
            abort(500, __('messages.country_created_failed'));
        }

        return response()->json(['message' => __('messages.country_created'), 'data' => $country], 201);
    }

    /**
     * Delete a single country.
     *
     * @param Request $request
     * @param int     $countryID
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $countryID)
    {
        $country = Country::find($countryID);

        if (\is_null($country)) {
            abort(404, __('error.404'));
        }

        try {
            $country->delete();
        } catch (\Exception $e) {
            abort(500, __('error.500'));
        }

        return response()->json(['message' => trans_choice('messages.deleted_countries', 1)]);
    }

    /**
     * Delete one or more countries.
     *
     * @param IntIdRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBulk(IntIdRequest $request)
    {
        $input = $request->only(['ids']);

        $count = Country::destroy($input['ids']);;
        return response()->json([
            'count'   => $count,
            'message' => trans_choice('messages.deleted_countries', $count)
        ]);
    }

    /**
     * Return a single country.
     *
     * @param Request $request
     * @param int     $countryID
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, $countryID)
    {
        $country = Country::find($countryID);

        if (\is_null($country)) {
            abort(404, __('error.404'));
        }

        return response()->json($country);
    }

    /**
     * Update a country.
     *
     * @param UpdateRequest $request
     * @param int           $countryID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, $countryID)
    {
        $input = $request->only([
            'code',
            'country',
        ]);

        $country = Country::find($countryID);

        if (\is_null($country)) {
            abort(404, __('error.404'));
        }

        try {
            $country->update($input);
        } catch (\Throwable $exception) {
            abort(500, __('error.500'));
        }

        return response()->json($country);
    }
}
