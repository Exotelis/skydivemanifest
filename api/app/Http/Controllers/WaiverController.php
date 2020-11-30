<?php

namespace App\Http\Controllers;

use App\Http\Requests\IntIdRequest;
use App\Http\Requests\Waiver\CreateRequest;
use App\Http\Requests\Waiver\SignRequest;
use App\Http\Requests\Waiver\UpdateRequest;
use App\Http\Requests\Waiver\WebSignRequest;
use App\Models\User;
use App\Models\Waiver;
use App\Traits\Paginate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    public function activeAll(Request $request)
    {
        $this->validatePagination($request->only('limit', 'page'));

        $waivers = QueryBuilder::for(
            Waiver::with(['texts' => function ($q) { $q->orderBy('position'); }])->whereIsActive(true)
        )
            ->allowedFilters(\App\Filters\WaiverActiveFilters::filters())
            ->allowedSorts(\App\Filters\WaiverActiveFilters::sorting())
            ->defaultSort('id')
            ->paginate($request->input('limit'));

        return response()->json($waivers);
    }

    /**
     * Get a single active waiver.
     *
     * @param Request $request
     * @param Waiver  $waiver
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeGet(Request $request, Waiver $waiver)
    {
        $activeFilter = QueryBuilder::for(
                Waiver::with(['texts' => function ($q) { $q->orderBy('position'); }])->whereIsActive(true)
            )
            ->allowedFilters(\App\Filters\WaiverActiveGetFilters::filters())
            ->find($waiver->id);

        return response()->json($activeFilter);
    }

    /**
     * Sign an active waiver.
     *
     * @param SignRequest $request
     * @param Waiver      $waiver
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeSign(SignRequest $request, Waiver $waiver)
    {
        $user = $request->user();

        try {
            $waiver->users()->syncWithoutDetaching([$user->id => ['signature' => $request->input('signature')]]);

            // Log message
            Log::info("[User-Waiver] User '{$user->logString()}' just signed waiver '{$waiver->logString()}'");
        } catch (\Throwable $e) {
            abort(500, __('error.500'));
        }

        return response()->json(
            ['message' => __('messages.waiver_signed', ['waiver' => $waiver->title])],
            201
        );
    }

    /**
     * Withdraw an active waiver.
     *
     * @param Request $request
     * @param Waiver  $waiver
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeWithdraw(Request $request, Waiver $waiver)
    {
        $user = $request->user();

        try {
            $waiver->users()->detach([$user->id]);

            // Log message
            Log::info("[User-Waiver] User '{$user->logString()}' withdraws the signature on waiver " .
                "'{$waiver->logString()}'");
        } catch (\Throwable $e) {
            abort(500, __('error.500'));
        }

        return response()->json(['message' => __('messages.waiver_withdraw')]);
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
     * Duplicate the waiver.
     *
     * @param Request $request
     * @param Waiver  $waiver
     * @return \Illuminate\Http\JsonResponse
     */
    public function duplicate(Request $request, Waiver $waiver)
    {
        $duplicate = null;

        try {
            $duplicate = $waiver->duplicateHasMany(['texts'], [
                'is_active' => 0,
                'title'     => $waiver->title . ' (' . __('messages.copy') . ')',
            ]);
        } catch (\Exception $e) {
            abort(500, __('error.500'));
        }

        return response()->json(['message' => __('messages.waiver_duplicate'), 'data' => $duplicate]);
    }

    /**
     * Render the web e-waiver view.
     *
     * @param Request $request
     * @param string  $language
     * @param int     $waiverID
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function eWaiversGet(Request $request, string $language, int $waiverID = null) {
        if (\is_null($waiverID)) {
            $waivers = Waiver::whereIsActive(true)
                ->with(['texts' => function ($q) use ($language) { $q->where('language_code', $language); }])
                ->get();

            return view('frontend.waivers.waivers', ['waivers' => $waivers->toArray(), 'language' => $language]);
        }

        // Can only sign active waivers!
        $waiver = Waiver::whereId($waiverID)
            ->whereIsActive(true)
            ->with(['texts' => function ($q) use ($language) {
                $q->where('language_code', $language)->orderBy('position');
            }])
            ->firstOrFail();

        return view('frontend.waivers.waiver', ['waiver' => $waiver->toArray(), 'language' => $language]);
    }

    /**
     * Create a signed waiver.
     *
     * @param WebSignRequest $request
     * @param string         $language
     * @param int            $waiverID
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function eWaiverSign(WebSignRequest $request, string $language, int $waiverID)
    {
        // Google reCaptcha handling
        if ($request->has('recaptcha_token') && ! empty(config('setting.google.recaptcha3.private'))) {
            $client = new \GuzzleHttp\Client();
            try {
                $response = $client->post(
                    'https://www.google.com/recaptcha/api/siteverify',
                    [
                        'query' => [
                            'remoteip' => $request->ip(),
                            'response' => $request->input('recaptcha_token'),
                            'secret'   => config('setting.google.recaptcha3.private'),
                        ],
                        'verify' => false, // Disable SSL
                    ]
                );
                $json = json_decode($response->getBody());

                if ($json->score < 0.5) {
                    return redirect()
                        ->back()
                        ->with('error', __('error.recaptcha3_score'));
                }
            } catch (\Exception $exception) {
                Log::error("[reCaptcha] Could not call reCaptcha v3 api in 'WaiverController'");
            }
        }

        // Get waiver
        $waiver = Waiver::find($waiverID);

        if (\is_null($waiver)) {
            // Abort if waiver is not found
            return redirect()->route('e-waivers.get', ['language' => $language]);
        }

        // Try to determine user and create signed waiver - either assign it to a user or leave it unassigned
        $user = null;

        // Find user by the ticket number
        if ($request->has('ticket_number')) {
            return redirect()
                ->back()
                ->with('error', 'Signing waivers with the ticket number has not been implemented yet.');
            // TODO find ticket -> get user of ticket -> assign signed waiver to user
            //      return with error if no ticket with the id was found!
        }

        if (\is_null($user)) {
            // If no ticket number was submitted, the email, first and last name fields must be present. Try to find a
            // user with these information.
            $user = User::whereEmail($request->input('email'))
                ->whereFirstname($request->input('firstname'))
                ->whereLastname($request->input('lastname'))
                ->first();
        }

        // Create new dataset - Either an unassigned waiver or a waiver attached to a user!
        if (\is_null($user)) {
            $waiver->unassignedWaivers()->create([
                'email'     => $request->input('email'),
                'firstname' => $request->input('firstname'),
                'ip'        => $request->ip(),
                'lastname'  => $request->input('lastname'),
                'signature' => $request->input('signature'),
            ]);
        } else {
            if ($waiver->users()->get()->contains($user)) {
                $waiver->users()->updateExistingPivot($user, []); // Update updated_at column
            } else {
                $waiver->users()->attach($user, ['signature' => $request->input('signature')]);
            }
        }

        return redirect()
            ->route('e-waivers.get', ['language' => $language])
            ->with('success', __('messages.waiver_signed', ['waiver' => $waiver->title]));
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

        return response()->json($waiver->withoutRelations());
    }
}
