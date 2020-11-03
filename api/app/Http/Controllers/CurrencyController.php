<?php

namespace App\Http\Controllers;

use App\Http\Requests\Currency\CreateRequest;
use App\Http\Requests\Currency\DeleteBulkRequest;
use App\Http\Requests\Currency\UpdateRequest;
use App\Models\Currency;
use App\Traits\Paginate;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class CurrencyController
 * @package App\Http\Controllers
 */
class CurrencyController extends Controller
{
    use Paginate;

    /**
     * Get a list of all currencies.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function all(Request $request)
    {
        $this->validatePagination($request->only('limit', 'page'));

        $countries = QueryBuilder::for(Currency::class)
            ->allowedFilters(\App\Filters\CurrencyFilters::filters())
            ->allowedSorts(\App\Filters\CurrencyFilters::sorting())
            ->defaultSort('code')
            ->paginate($request->input('limit'));

        return response()->json($countries);
    }

    /**
     * Create a new currency.
     *
     * @param CreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateRequest $request)
    {
        $currency = null;

        try {
            $currency = Currency::create($request->validated());
        } catch (\Throwable $exception) {
            abort(500, __('messages.currency_created_failed'));
        }

        return response()->json(['message' => __('messages.currency_created'), 'data' => $currency], 201);
    }

    /**
     * Delete a single currency.
     *
     * @param Request  $request
     * @param Currency $currency
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, Currency $currency)
    {
        try {
            $currency->delete();
        } catch (\Exception $e) {
            abort(500, __('error.500'));
        }

        return response()->json(['message' => trans_choice('messages.deleted_currencies', 1)]);
    }

    /**
     * Delete one or more currencies.
     *
     * @param DeleteBulkRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBulk(DeleteBulkRequest $request)
    {
        $input = $request->only(['codes']);

        $count = Currency::destroy($input['codes']);;
        return response()->json([
            'count'   => $count,
            'message' => trans_choice('messages.deleted_currencies', $count)
        ]);
    }

    /**
     * Return a single currency.
     *
     * @param Request  $request
     * @param Currency $currency
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, Currency $currency)
    {
        return response()->json($currency);
    }

    /**
     * Update a currency.
     *
     * @param UpdateRequest $request
     * @param Currency      $currency
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Currency $currency)
    {
        try {
            $currency->update($request->validated());
        } catch (\Throwable $exception) {
            abort(500, __('error.500'));
        }

        return response()->json($currency);
    }
}
