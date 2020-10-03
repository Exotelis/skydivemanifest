<?php

namespace App\Events\User;

use Illuminate\Queue\SerializesModels;

/**
 * Class Delete
 * @package App\Events\User
 */
class Delete
{
    use SerializesModels;

    /**
     * The admin instance.
     *
     * @var \App\Models\User
     */
    public $deletedBy;

    /**
     * The email address of the deleted user.
     *
     * @var string
     */
    public $email;

    /**
     * The firstname of the deleted user.
     *
     * @var string
     */
    public $firstname;

    /**
     * The id of the deleted user.
     *
     * @var int
     */
    public $id;

    /**
     * The locale of the deleted user.
     *
     * @var int
     */
    public $locale;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\User $deletedBy
     * @return void
     */
    public function __construct($user, $deletedBy)
    {
        $this->deletedBy = $deletedBy;
        $this->email = $user->email;
        $this->firstname = $user->firstname;
        $this->id = $user->id;
        $this->locale = $user->locale;
    }
}
