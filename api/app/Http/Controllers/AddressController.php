<?php

namespace App\Http\Controllers;

use App\Http\Requests\Address\CreateRequest;
use App\Http\Requests\Address\UpdateRequest;
use App\Http\Requests\IntIdRequest;
use App\Models\Address;
use App\Models\User;
use App\Traits\Paginate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class AddressController
 * @package App\Http\Controllers
 */
class AddressController extends Controller
{
    use Paginate;

    /**
     * Get a list of all addresses of a user.
     *
     * @param Request $request
     * @param User    $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function all(Request $request, User $user)
    {
        $this->validatePagination($request->only('limit', 'page'));

        $addresses = QueryBuilder::for($user->addresses())
            ->with(['country', 'region'])
            ->allowedFilters(\App\Filters\AddressFilters::filters())
            ->allowedSorts(\App\Filters\AddressFilters::sorting())
            ->defaultSort('id')
            ->paginate($request->input('limit'));

        return response()->json($addresses);
    }

    /**
     * Create a new address for a specific user.
     *
     * @param CreateRequest $request
     * @param User          $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateRequest $request, User $user)
    {
        $address = null;

        try {
            DB::beginTransaction();

            $address = $user
                ->addresses()
                ->create($request->validated())
                ->withoutRelations()
                ->load(['country', 'region']);

            // Set users default addresses if necessary
            if ($request->input('default_invoice')) {
                $user->defaultInvoice()->associate($address);
            }
            if ($request->input('default_shipping')) {
                $user->defaultShipping()->associate($address);
            }
            $user->saveOrFail();

            DB::commit();
        } catch (\Throwable $exception) {
            abort(500, __('messages.address_created_failed'));
        }

        return response()->json(['message' => __('messages.address_created'), 'data' => $address], 201);
    }

    /**
     * Delete a single role of a specific user.
     *
     * @param Request $request
     * @param User    $user
     * @param Address $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, User $user, Address $address)
    {
        try {
            DB::beginTransaction();

            $address->delete();

            if ($user->isDefaultInvoice($address)) {
                $user->defaultInvoice()->dissociate();
            }
            if ($user->isDefaultShipping($address)) {
                $user->defaultShipping()->dissociate();
            }
            $user->save();

            DB::commit();
        } catch (\Exception $e) {
            abort(500, __('error.500'));
        }

        return response()->json(['message' => trans_choice('messages.deleted_addresses', 1)]);
    }

    /**
     * Delete one or more addresses of a specific user.
     *
     * @param IntIdRequest $request
     * @param User         $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBulk(IntIdRequest $request, User $user)
    {
        $input = $request->only(['ids']);
        $count = 0;

        foreach ($user->addresses as $address) {
            if (\in_array($address->id, $input['ids']) && $address->delete()) {
                $count++;
            }

            if ($user->isDefaultInvoice($address)) {
                $user->defaultInvoice()->dissociate();
            }
            if ($user->isDefaultShipping($address)) {
                $user->defaultShipping()->dissociate();
            }
            $user->save();
        }

        return response()->json([
            'count'   => $count,
            'message' => trans_choice('messages.deleted_addresses', $count)
        ]);
    }

    /**
     * Return a single address of a specific user.
     *
     * @param Request $request
     * @param User    $user
     * @param Address $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, User $user, Address $address)
    {
        return response()->json($address->load(['country', 'region']));
    }

    /**
     * Update a address of a specific user.
     *
     * @param UpdateRequest $request
     * @param User          $user
     * @param Address       $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, User $user, Address $address)
    {
        try {
            DB::beginTransaction();

            $address->update($request->validated());

            // Set users default addresses if necessary
            if (! \is_null($request->input('default_invoice'))) {
                (bool) $request->input('default_invoice') ?
                    $user->defaultInvoice()->associate($address) :
                    $user->defaultInvoice()->dissociate() ;
            }
            if (! \is_null($request->input('default_shipping'))) {
                (bool) $request->input('default_shipping') ?
                    $user->defaultShipping()->associate($address) :
                    $user->defaultShipping()->dissociate();
            }
            $user->saveOrFail();

            DB::commit();
        } catch (\Throwable $exception) {
            abort(500, __('error.500'));
        }

        return response()->json($address->withoutRelations()->load(['country', 'region']));
    }

    /**
     *
     * Me / Personal methods
     *
     */

    /**
     * Get a list of all addresses of the current user.
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
     * Create a new address for the current user.
     *
     * @param CreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function meCreate(CreateRequest $request)
    {
        return $this->create($request, $request->user());
    }

    /**
     * Delete an address of the current user.
     *
     * @param Request $request
     * @param Address $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function meDelete(Request $request, Address $address)
    {
        return $this->delete($request, $request->user(), $address);
    }

    /**
     * Delete one or more addresses of the current user.
     *
     * @param IntIdRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function meDeleteBulk(IntIdRequest $request)
    {
        return $this->deleteBulk($request, $request->user());
    }

    /**
     * Return a single address of the current user.
     *
     * @param Request $request
     * @param Address $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function meGet(Request $request, Address $address)
    {
        return $this->get($request, $request->user(), $address);
    }

    /**
     * Return a single address of the current user.
     *
     * @param UpdateRequest $request
     * @param Address       $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function meUpdate(UpdateRequest $request, $address)
    {
        return $this->update($request, $request->user(), $address);
    }
}
