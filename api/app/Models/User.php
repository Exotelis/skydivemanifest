<?php

namespace App\Models;

use App\Contracts\Auth\CanBeDisabled as CanBeDisabledContract;
use App\Contracts\Auth\MustChangePassword as MustChangePasswordContract;
use App\Contracts\Auth\CanBeLockedTemporarily as CanBeLockedContract;
use App\Contracts\Logable;
use App\Contracts\User\MustVerifyEmail as MustVerifyEmailContract;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Traits\CanBeLockedTemporarily as CanBeLocked;
use App\Traits\ModelDiff;
use App\Traits\MustVerifyEmail;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\RefreshToken;

/**
 * Class User
 * @package App\Models
 *
 * @property string $broker
 * @property int $id
 * @property int|null $default_invoice
 * @property int|null $default_shipping
 * @property Carbon|null $deleted_at
 * @property Carbon $dob date of birth
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property int $failed_logins
 * @property string $firstname
 * @property string $gender
 * @property bool $is_active
 * @property Carbon|null $last_logged_in
 * @property string $lastname
 * @property string $locale
 * @property Carbon|null $lock_expires
 * @property string|null $middlename
 * @property string|null $mobile
 * @property string|null $password
 * @property bool $password_change Force password change
 * @property string|null $phone
 * @property int|null $role_id
 * @property string|null $username
 * @property string|null $timezone
 * @property boolean $tos Terms of Service accepted
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Address[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read Address $defaultInvoice
 * @property-read Address $defaultShipping
 * @property-read string $name
 * @property-read \Illuminate\Database\Eloquent\Collection|Qualification[] $qualifications
 * @property-read int|null $qualifications_count
 * @property-read Role|null $role
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Waiver[] $waivers
 * @property-read int|null $waivers_count
 * @method static \Illuminate\Database\Eloquent\Builder|User findByAuthname($identifier)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDefaultInvoice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDefaultShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifyHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFailedLogins($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoggedIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLockExpires($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMiddlename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePasswordChange($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTos($value)
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class User extends Model implements
    AuthenticatableContract,
    CanBeDisabledContract,
    CanBeLockedContract,
    CanResetPasswordContract,
    HasLocalePreference,
    Logable,
    MustChangePasswordContract,
    MustVerifyEmailContract
{
    use Authenticatable, CanBeLocked, CanResetPassword, HasApiTokens, HasFactory, ModelDiff, MustVerifyEmail,
        Notifiable, SoftDeletes;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'name',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'default_invoice'   => null,
        'default_shipping'  => null,
        'deleted_at'        => null,
        'email_verified_at' => null,
        'failed_logins'     => 0,
        'gender'            => 'u',
        'is_active'         => true,
        'last_logged_in'    => null,
        'locale'            => 'en',
        'lock_expires'      => null,
        'middlename'        => null,
        'mobile'            => null,
        'password'          => null,
        'password_change'   => false,
        'phone'             => null,
        'role_id'           => 2,
        'username'          => null,
        'timezone'          => null,
        'tos'               => false,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'dob'             => 'date:Y-m-d',
        'failed_logins'   => 'integer',
        'is_active'       => 'boolean',
        'password_change' => 'boolean',
        'tos'             => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
        'email_verified_at',
        'last_logged_in',
        'lock_expires',
    ];

    /**
     * Guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'default_invoice',
        'default_shipping',
        'failed_logins',
        'role_id',
        'password',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 50;

    /**
     * Get the full name of the user.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        if (! \is_null($this->middlename)) {
            return "{$this->lastname}, {$this->firstname} {$this->middlename}";
        }

        return "{$this->lastname}, {$this->firstname}";
    }

    /**
     * Set the birth date and convert it to Carbon.
     *
     * @param  string $value
     * @return void
     */
    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = Carbon::make($value)->toDateString();
    }

    /**
     * Set the password and hash it.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get the addresses of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany('App\Models\Address');
    }

    /**
     * Determine if the user has a given address.
     *
     * @param  Address $address
     * @return boolean
     */
    public function hasAddress(Address $address)
    {
        return $this->addresses->contains($address);
    }

    /**
     * Get the default_invoice address of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function defaultInvoice()
    {
        return $this->belongsTo('App\Models\Address', 'default_invoice');
    }

    /**
     * Determine if the given address is the default invoice address
     *
     * @param  Address $address
     * @return boolean
     */
    public function isDefaultInvoice(Address $address)
    {
        return $this->default_invoice === $address->getKey();
    }

    /**
     * Get the default_shipping address of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function defaultShipping()
    {
        return $this->belongsTo('App\Models\Address', 'default_shipping');
    }

    /**
     * Determine if the given address is the default shipping address
     *
     * @param  Address $address
     * @return boolean
     */
    public function isDefaultShipping(Address $address)
    {
        return $this->default_shipping === $address->getKey();
    }

    /**
     * The qualifications that belong to the user.
     */
    public function qualifications()
    {
        return $this->belongsToMany(
            'App\Models\Qualification',
            null,
            null,
            'qualification_slug',
            null,
            'slug'
        )->withTimestamps();
    }

    /**
     * Get the role that owns the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    /**
     * The signed waivers that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function waivers()
    {
        return $this->belongsToMany('App\Models\Waiver')
            ->as('signature')
            ->withTimestamps();
    }
    /**
     * Scope a query to filter for the born after date
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $dob
     * @return \Illuminate\Database\Eloquent\Builder|User
     */
    public function scopeBornAfter($query, $dob)
    {
        return $query->where('dob', '>=', Carbon::parse($dob));
    }

    /**
     * Scope a query to filter for the age
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $age
     * @return \Illuminate\Database\Eloquent\Builder|User
     */
    public function scopeAge($query, $age)
    {
        return $query->where('dob', '<=', Carbon::now()->subYears($age))
            ->where('dob', '>', Carbon::now()->subYears($age)->subYear());
    }

    /**
     * Scope a query to filter for the born before date
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $dob
     * @return \Illuminate\Database\Eloquent\Builder|User
     */
    public function scopeBornBefore($query, $dob)
    {
        return $query->where('dob', '<=', Carbon::parse($dob));
    }

    /**
     * Scope a query to filter for the dob
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $dob
     * @return \Illuminate\Database\Eloquent\Builder|User
     */
    public function scopeDob($query, $dob)
    {
        return $query->where('dob', '=', Carbon::make($dob)->toDateString());
    }

    /**
     * Scope a query to filter verified accounts
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $verified
     * @return \Illuminate\Database\Eloquent\Builder|User
     */
    public function scopeEmailVerified($query, $verified)
    {
        if ($verified === '1' || $verified === 'true' || $verified === true) {
            return $query->where('email_verified_at', '<>', null);
        }

        return $query->where('email_verified_at', '=', null);
    }

    /**
     * Scope a query to filter for the full name
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $name
     * @return \Illuminate\Database\Eloquent\Builder|User
     */
    public function scopeFullName($query, $name)
    {
        return $query->where(DB::raw('CONCAT(firstname," ",lastname)'), 'LIKE', '%'.$name.'%')
            ->orWhere(
                DB::raw('CONCAT(firstname," ",middlename," ",lastname)'),'LIKE', '%'.$name.'%'
            )
            ->orWhere(DB::raw('CONCAT(lastname," ",firstname)'), 'LIKE', '%'.$name.'%')
            ->orWhere(
                DB::raw('CONCAT(lastname," ",firstname," ",middlename)'), 'LIKE', '%'.$name.'%'
            );
    }

    /**
     * Scope a query to filter users older than
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $age
     * @return \Illuminate\Database\Eloquent\Builder|User
     */
    public function scopeOlderThan($query, $age)
    {
        return $query->where('dob', '<=', Carbon::now()->subYears($age));
    }

    /**
     * Scope a query to filter users younger than
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $age
     * @return \Illuminate\Database\Eloquent\Builder|User
     */
    public function scopeYoungerThan($query, $age)
    {
        return $query->where('dob', '>', Carbon::now()->subYears($age)->subYear());
    }

    /**
     * Username and email can be used to authenticate the user with OAuth.
     *
     * @param  $identifier
     * @return mixed
     */
    public function findForPassport($identifier)
    {
        return $this->where('email', $identifier)->orWhere('username', $identifier)->first();
    }

    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale()
    {
        return $this->locale;
    }

    /**
     * Validate the password of the user for the Passport password grant.
     *
     * @param  string $password
     * @return bool
     */
    public function validateForPassportPasswordGrant($password)
    {
        return Hash::check($password, $this->password);
    }

    /**
     * Determine if the user is disabled.
     *
     * @return bool
     */
    public function isDisabled()
    {
        return ! $this->is_active;
    }

    /**
     * Determine if the password must be changed.
     *
     * @return bool
     */
    public function mustChangePassword()
    {
        return $this->password_change;
    }

    /**
     * Update the last login time.
     *
     * @return bool
     */
    public function setLastLogin()
    {
        $this->last_logged_in = Carbon::now();

        if ($this instanceof CanBeLockedContract) {
            return $this->resetLoginAttempts();
        }

        return $this->save();
    }

    /**
     * Sign the user out.
     *
     * @return void
     */
    public function signOut()
    {
        foreach($this->tokens as $token) {
            // Do not sign out personal access client tokens
            if ($token->client_id === config('passport.personal_access_client.id')) {
                continue;
            }

            // Revoke token and refresh tokens, if some exist!
            $revoked = $token->revoke();
            $refreshToken = RefreshToken::firstWhere('access_token_id', $token->id);
            if (!\is_null($refreshToken)) {
                $refreshToken->revoke();
            }
            if (! $revoked) {
                abort(500, __('auth.oauth_revoke'));
            }
        }
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Determine if terms of service have been accepted.
     *
     * @return bool
     */
    public function tosAccepted()
    {
        return $this->tos;
    }

    /**
     * Get the values of the most important attributes of the model.
     *
     * @return string
     */
    public function logString()
    {
        return "{$this->id}|{$this->email}|{$this->lastname}|{$this->firstname}";
    }
}
