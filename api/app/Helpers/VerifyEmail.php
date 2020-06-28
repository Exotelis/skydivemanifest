<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Class VerifyEmail
 * @package App\Helpers
 */
class VerifyEmail
{
    /**
     * Config that is used. Default is users.
     *
     * @var string
     */
    protected $config = 'users';

    /**
     * @param string|null $config
     * @return $this
     */
    public function config($config = null)
    {
        if (! is_null($config)) {
            $this->config = $config;
        }

        return $this;
    }

    /**
     * Create a new email change request and return a token.
     *
     * @param $email
     * @param $newEmail
     * @return string
     */
    public function create($email, $newEmail)
    {
        $this->delete($email);

        $token = $this->createToken();

        DB::table($this->table())->insert([
            'email'      => $email,
            'new_email'  => $newEmail,
            'token'      => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        return $token;
    }

    /**
     * Create a new token for the email change request.
     *
     * @return string
     */
    protected function createToken()
    {
        $key = config('app.key');

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        return hash_hmac('sha256', Str::random(40), $key);
    }

    /**
     * Delete an existing email address change request.
     *
     * @param  string $email
     * @return bool
     */
    public function delete($email)
    {
        if ($this->exists($email)) {
            return (bool) DB::table($this->table())
                ->where('email', '=', $email)
                ->delete();
        }

        return false;
    }

    /**
     * Determine if a email address change request exist for the given email address.
     *
     * @param  $email
     * @return bool
     */
    public function exists($email)
    {
        return (bool) DB::table($this->table())->where('email', '=', $email)->count();
    }

    /**
     * Return the time in minutes, when a token should expire. Default is 1 day.
     * If an user is submitted, the token expires not before the user is deleted. Default is 10 days.
     *
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return int
     */
    public function expires($user = null)
    {
        $config = $this->getConfiguration();

        if (!is_null($user) && is_null($user->email_verified_at)) {
            return config('app.users.delete_unverified_after') * 24 * 60;
        }

        return $config['expire'] ?? 1440;
    }

    /**
     * Return the user that is assigned to a token.
     *
     * @param  string $token
     * @return mixed
     */
    public function findUser($token)
    {
        $provider = $this->getProvider();

        // Currently only eloquent is supported
        if ($provider['driver'] !== 'eloquent') {
            return null;
        }

        $requests = DB::table($this->table())->select(['email', 'token'])->get();

        $userEmail = null;

        foreach ($requests as $request) {
            if (Hash::check($token, $request->token)) {
                $userEmail = $request->email;
                break;
            }
        }

        if (is_null($userEmail)) {
            abort(400, __('error.email_token_invalid'));
        }

        return $provider['model']::whereEmail($userEmail)->first();
    }

    /**
     * Get the email address change request.
     *
     * @param $email
     * @return \Illuminate\Database\Eloquent\Model|object|null
     */
    public function get($email)
    {
        if (! $this->exists($email)) {
            return null;
        }

        return DB::table($this->table())->select()->where('email', '=', $email)->first();
    }

    /**
     * Return the email address changer configuration.
     *
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function getConfiguration()
    {
        $config = config('auth.email_changes.' . $this->config);

        if(is_null($config)) {
            abort(500, "Email address changer [{$this->config}] is not defined.");
        }

        return $config;
    }

    /**
     * Return the provider used by the configuration.
     *
     * @return array
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function getProvider()
    {
        $config = $this->getConfiguration();
        $provider = config('auth.providers.' . $config['provider']);

        if(is_null($provider)) {
            abort(500, "Provider [{$config['provider']}] is not defined.");
        }

        return $provider;
    }

    /**
     * Determine if the email change request has been created just recently.
     *
     * @param $email
     * @return bool
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function createdRecently($email)
    {
        if (! $this->exists($email)) {
            abort('400', __('error.email_token_not_found'));
        }

        $request = $this->get($email);
        return Carbon::parse($request->created_at)->addSeconds($this->throttle())->isFuture();
    }

    /**
     * Return the table name where the email address changes are saved.
     *
     * @return string|null
     */
    protected function table()
    {
        $config = $this->getConfiguration();
        return $config['table'] ?? null;
    }

    /**
     * Return the throttle time in seconds.
     *
     * @return int
     */
    public function throttle()
    {
        $config = $this->getConfiguration();
        return $config['throttle'] ?? 300;
    }

    /**
     * Verify the new email address of a user.
     *
     * @param $token
     * @param \Illuminate\Database\Eloquent\Model $user
     * @return bool
     * @throws \Throwable
     */
    public function verify($token, $user = null)
    {
        $user = is_null($user) ? $this->findUser($token) : $user;

        if (is_null($user)) {
            return false;
        }

        $request = $this->get($user->email);

        if (Carbon::parse($request->created_at)->addMinutes($this->expires($user))->isPast())
        {
            abort(400, __('error.email_token_expired'));
        }

        DB::beginTransaction();

        $this->delete($user->email);

        $user->email = $request->new_email;
        $user->email_verified_at = Carbon::now();
        $user->saveOrFail();

        DB::commit();

        return true;
    }
}
