<?php

namespace App\Observers;

use App\Mail\DeleteUser as Mailable;
use App\Models\Address;
use App\Models\User;
use App\Notifications\CreateUser as CreateUserNotification;
use App\Notifications\SoftDeleteUser as SoftDeleteUserNotification;
use App\Notifications\RestoreUser as RestoreUserNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Class UserObserver
 * @package App\Observers
 */
class UserObserver extends BaseObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  User  $user
     * @return void
     */
    public function created(User $user)
    {
        Log::info("[User] '{$user->logString()}' has been created by '{$this->executedBy}'");

        // Send notifications - Generate new password is none has been set.
        $password = \is_null($user->password) ? Str::random(12) : null;
        if (! \is_null($password)) {
            $user->password = $password;
            $user->saveQuietly();
        }

        $user->notify(new CreateUserNotification($password));
        if ($user instanceof \App\Contracts\User\MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification($user->email);
        }
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  User  $user
     * @return void
     */
    public function updated(User $user)
    {
        $diff = $user->getDiff([
            'email_verified_at',
            'lock_expires',
            'password',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);

        // Only log the update event in cases that are not covered by other log messages
        if (! empty($diff)) {
            // Do not display diff because of privacy reasons
            Log::info("[User] '{$user->logString()}' has been updated by '{$this->executedBy}'");
        }
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        // Don't run is force deleting!
        if ($user->isForceDeleting()) {
            return;
        }

        Log::info("[User] '{$user->logString()}' has been marked as deleted by '{$this->executedBy}'");

        // Sign user out
        $user->signOut();

        // Generate and store restore token
        $key = config('app.key');
        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        $token = hash_hmac('sha256', Str::random(40), $key);

        DB::table('restore_users')->updateOrInsert(
            [
                'user_id'    => $user->id,
            ], [
                'token'      => Hash::make($token),
                'user_id'    => $user->id,
                'created_at' => Carbon::now(),
            ]
        );

        // Send notification
        $user->notify(new SoftDeleteUserNotification($token));
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  User  $user
     * @return void
     */
    public function restored(User $user)
    {
        Log::info("[User] '{$user->logString()}' has been restored by '{$this->executedBy}'");

        // Delete restore token
        DB::table('restore_users')->where('user_id', '=', $user->id)->delete();

        // Send notification
        $user->notify(new RestoreUserNotification());
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        // Delete related models as well
        Address::destroy($user->addresses->pluck('id')); // Delete related addresses
        // Detach deleted user from all qualifications.
        $user->qualifications()->detach($user->qualifications->pluck('slug')->toArray());
        $user->waivers()->detach($user->waivers->pluck('id')->toArray()); // Detach all signed waivers.

        Log::info("[User] '{$user->logString()}' has been deleted permanently by '{$this->executedBy}'");

        // Delete restore token
        DB::table('restore_users')->where('user_id', '=', $user->id)->delete();

        // Send deletion mail
        $mail = (new Mailable($user->email, $user->firstname, $user->locale))->onQueue('mail');
        Mail::to($user->email)->send($mail);
    }
}
