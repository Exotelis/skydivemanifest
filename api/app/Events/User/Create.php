<?php

namespace App\Events\User;

use Illuminate\Queue\SerializesModels;

/**
 * Class Create
 * @package App\Events\User
 */
class Create
{
    use SerializesModels;

    /**
     * The admin instance.
     *
     * @var \App\Models\User
     */
    public $createdBy;

    /**
     * The password that got created for the user.
     *
     * @var string
     */
    public $password;

    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\User $user
     * @param  String $password
     * @param  \App\Models\User $createdBy
     * @return void
     */
    public function __construct($user, $password, $createdBy)
    {
        $this->createdBy = $createdBy;
        $this->password = $password;
        $this->user = $user;
    }
}
