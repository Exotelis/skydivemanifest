<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * The user instance.
     *
     * @var \Illuminate\Database\Eloquent\Builder|User
     */
    protected $user;

    /**
     * Sign the user in.
     *
     * @param  LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function login(LoginRequest $request)
    {
        $input = $request->only(['username', 'password']);

        // Find user
        $this->user = User::findByAuthname($input['username']);

        // Check if user exists
        if (is_null($this->user) || ! $this->user instanceof User) {
            abort(401, __('auth.failed'));
        }

        // Check if account is temporarily locked - Prevent brute force attacks
        if ($this->user instanceof \App\Contracts\Auth\CanBeLockedTemporarily) {
            if ($this->user->isLocked()) {
                $expires = $this->user->lockExpires()->longAbsoluteDiffForHumans();
                abort(401, __('error.temporarily_locked', ['expires' => $expires]));
            }
        }

        // Check if password is correct
        if (! Hash::check($input['password'], $this->user->password)) {
            if ($this->user instanceof \App\Contracts\Auth\CanBeLockedTemporarily) {
                $this->user->failedLoginAttempt();
            }

            abort(401, __('auth.failed'));
        }

        /**
         * If account is not locked, inactive or password must reset then proceed.
         */
        $response = $this->forwardLoginRequest($input['username'], $input['password']);
        $response = $this->attachUserInformation($response);

        // Login was successful, set last login date to now and reset the failed login attempts
        $this->user->setLastLogin();

        // If the request was an ajax call, response with cookies (webapp)
        if ($request->ajax()) {
            return $this->respondWithCookies($response);
        }

        return response()->json($response);
    }

    /**
     * Sign the user out.
     *
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function logout()
    {
        // Check if a user is authenticated, if not it could be a client credential token.
        $user = Auth::user();

        if (! is_null($user) && $user instanceof User) {
            $user->signOut();

            return response()->json(['message' => __('messages.signed_out')]);
        }

        abort(400, __('error.could_not_sign_out'));
    }

    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $input = $request->only([
            'dob',
            'email',
            'firstname',
            'gender',
            'lastname',
            'locale',
            'password',
            'username',
            'timezone',
        ]);
        $newUser = null;

        try {
            DB::beginTransaction();

            $newUser = User::create($input);
            $role = Role::findOrFail(defaultRole());
            $newUser->role()->associate($role);

            event(new \Illuminate\Auth\Events\Registered($newUser));

            DB::commit();
        } catch (\Exception $exception) {
            abort(500, __('auth.failed_registration'));
        }

        return response()->json(['message' => __('auth.registration_successful'), 'data' => $newUser], 201);
    }

    /**
     * Attach user information to the response.
     *
     * @param  array $response
     * @return array
     */
    protected function attachUserInformation(array $response)
    {
        // TODO would be better if those information could be added as claims to the token directly. Keep an eye on:
        //      https://github.com/laravel/passport/issues/94
        //      https://github.com/corbosman/laravel-passport-claims
        $response['user'] = [
            'id'          => $this->user->id,
            'email'       => $this->user->email,
            'username'    => $this->user->username,
            'firstname'   => $this->user->firstname,
            'lastname'    => $this->user->lastname,
            'gender'      => $this->user->gender,
            'locale'      => $this->user->locale,
            'timezone'    => $this->user->timezone,
        ];

        return $response;
    }

    /**
     * Forward the login request to the oauth route. This approach keeps the client_secret safe.
     * Returns the response in case of success or an exception.
     *
     * @param  string $username
     * @param  string $password
     * @return array
     */
    protected function forwardLoginRequest($username, $password)
    {
        $route = route('passport.token', null, false);

        $data = [
            'grant_type' => 'password',
            'client_id' => config('auth.oauth.password_client.id'),
            'client_secret' => config('auth.oauth.password_client.secret'),
            'username' => $username,
            'password' => $password,
            'scope' => $this->user->role->permissions->pluck('slug')->toArray(),
        ];

        $tokenRequest = Request::create($route, 'post', $data);
        $tokenResponse = app()->handle($tokenRequest);
        $tokenContent = [];

        if ($tokenResponse instanceof \Illuminate\Http\Response) {
            if (! $tokenResponse->isSuccessful()) {
                abort(401, __('auth.failed'));
            }
            $tokenContent = json_decode($tokenResponse->content(), true);
        }

        return $tokenContent;
    }

    /**
     * Send response with cookies attached.
     *
     * @param $response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithCookies($response)
    {
        // Split token in its parts - [0] => header; [1] => payload; [2] => signature
        $jwt = explode('.', $response['access_token']);

        // Set cookies to protect against XSS and CSRF
        $host = getenv('HTTP_HOST');
        $domain = $host && $host !== 'localhost' ? $host : false;
        $xsrfToken = new Cookie(
            "XSRF-TOKEN",
            $jwt[0] . '.' . $jwt[1],
            Carbon::now()->addSeconds($response['expires_in']),
            '/',
            $domain,
            true,
            false);
        $authToken = new Cookie(
            "AUTH-TOKEN",
            $jwt[2],
            0,
            '/',
            $domain,
            true,
            true);

        return response()->json($response)->withCookie($xsrfToken)->withCookie($authToken);
    }
}
