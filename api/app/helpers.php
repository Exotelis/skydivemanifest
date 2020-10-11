<?php

if (! function_exists('appName')) {
    /**
     * Return the app name.
     *
     * @return string
     */
    function appName()
    {
        return config('app.name');
    }
}

if (! function_exists('appSupportMail')) {
    /**
     * Return the app support mail.
     *
     * @return string
     */
    function appSupportMail()
    {
        return config('mail.support_mail');
    }
}

if (! function_exists('appTimezone')) {
    /**
     * Return the app timezone.
     *
     * @return string
     */
    function appTimezone()
    {
        return config('app.timezone');
    }
}

if (! function_exists('apiVersion')) {
    /**
     * Return the api prefix.
     *
     * @return string
     */
    function apiVersion()
    {
        return config('app.api_version');
    }
}

if (! function_exists('allowMultipleTokens')) {
    /**
     * Determine if user is allowed to have multiple access tokens.
     *
     * @return bool
     */
    function allowMultipleTokens()
    {
        return config('passport.allow_multiple_tokens');
    }
}

if (! function_exists('deleteInactiveUsers')) {
    /**
     * Determine after how many months inactive users should be deleted.
     *
     * @return int
     */
    function deleteInactiveUsers()
    {
        return config('app.users.delete_inactive_after');
    }
}

if (! function_exists('deleteUnverifiedUsers')) {
    /**
     * Determine after how many days unverified users should be deleted.
     *
     * @return int
     */
    function deleteUnverifiedUsers()
    {
        return config('app.users.delete_unverified_after');
    }
}

if (! function_exists('recoverUsers')) {
    /**
     * Determine how long users can recover their accounts.
     *
     * @return int
     */
    function recoverUsers()
    {
        return config('app.users.recover');
    }
}

if (! function_exists('frontendUrl')) {
    /**
     * Return the api prefix.
     *
     * @return string
     */
    function frontendUrl()
    {
        return config('app.frontend_url');
    }
}

if (! function_exists('regexStrongPassword')) {
    /**
     * Return the strong password validation regex.
     * At least 1 lowercase alphabetical character.
     * At least 1 uppercase alphabetical character.
     * At least 1 numeric character.
     * At least one special character.
     *
     * @return string
     */
    function regexStrongPassword()
    {
        return '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!"§\$%&\/=\?\^@\*\+~#_-])(?=.{8,})/';
    }
}

if (! function_exists('regexMediumPassword')) {
    /**
     * Return the medium password validation regex.
     * At least 1 lowercase alphabetical character.
     * At least 1 uppercase alphabetical character.
     * At least 1 numeric character.
     * At least 6 characters long.
     *
     * @return string
     */
    function regexMediumPassword()
    {
        return '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.{6,})/';
    }
}

if (! function_exists('regexWeakPassword')) {
    /**
     * Return the weak password validation regex.
     * At least 6 characters long.
     *
     * @return string
     */
    function regexWeakPassword()
    {
        return '/^.{6,}$/';
    }
}

if (! function_exists('defaultPasswordStrength')) {
    /**
     * Return the name of the password regex function.
     *
     * @return string
     */
    function defaultPasswordStrength()
    {
        return config('auth.password_strength');
    }
}

if (! function_exists('passwordStrength')) {
    /**
     * Callback for the password strength function.
     *
     * @return mixed
     */
    function passwordStrength()
    {
        return call_user_func(defaultPasswordStrength());
    }
}

if (! function_exists('adminRole')) {
    /**
     * Return the id of the admin role.
     *
     * @return int
     */
    function adminRole()
    {
        return config('app.groups.admin');
    }
}

if (! function_exists('defaultRole')) {
    /**
     * Return id of the default user role.
     * Is also hard coded in the App\Models\User model
     *
     * @return int
     */
    function defaultRole()
    {
        return config('app.groups.user');
    }
}

if (! function_exists('lockAccountAfter')) {
    /**
     * Lock the account after x failed login attempts.
     *
     * @return int
     */
    function lockAccountAfter()
    {
        return config('auth.lock.after_tries');
    }
}

if (! function_exists('lockAccountFor')) {
    /**
     * Lock the account for x minutes.
     *
     * @return int
     */
    function lockAccountFor()
    {
        return config('auth.lock.for_minutes');
    }
}

if (! function_exists('defaultLocale')) {
    /**
     * Return the default locale.
     *
     * @return string
     */
    function defaultLocale()
    {
        return config('app.locale');
    }
}

if (! function_exists('validLocales')) {
    /**
     * A list of the valid locales.
     *
     * @return string[]
     */
    function validLocales()
    {
        return config('app.valid_locales');
    }
}

if (! function_exists('validGender')) {
    /**
     * A list of the valid gender.
     *
     * @return string[]
     */
    function validGender()
    {
        return ['m','f','d','u'];
    }
}

if (! function_exists('validRoles')) {
    /**
     * A list of the valid roles.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function validRoles($user = null)
    {
        if (\is_null($user)) {
            return new \Illuminate\Database\Eloquent\Collection();
        }

        if ($user->role_id === adminRole()) {
            return \App\Models\Role::all();
        }

        return \App\Models\Role::find(array_unique([defaultRole(), $user->role_id]));
    }
}

if (! function_exists('isDigit')) {
    /**
     * Determine if the given subject is a digit.
     *
     * @param  string $subject
     * @return bool
     */
    function isDigit($subject)
    {
        return (bool) preg_match('/^-?\d+$/', $subject);
    }
}

if (! function_exists('getModelDiff')) {
    /**
     * Get the diff of model on update
     *
     * @param  Illuminate\Database\Eloquent\Model $model
     * @param  array $except
     * @param  boolean $asString Determines if the diff should be returned as string
     * @return array|string
     */
    function getModelDiff($model, $except = [], $asString = false)
    {
        $dirty = Illuminate\Support\Arr::except($model->getDirty(), $except);
        $diff = $asString ? '' : [];

        foreach ($dirty as $key => $value) {
            $original = $model->getOriginal($key);

            if ($asString) {
                $diff .= "{$key}:[-]{$original}[+]{$value}|";
            } else {
                $diff[$key] = [
                    'old' => $original,
                    'new' => $value,
                ];
            }
        }

        if (is_string($diff)) {
            $diff = Illuminate\Support\Str::of($diff)->rtrim('|');
        }

        return $diff;
    }
}
