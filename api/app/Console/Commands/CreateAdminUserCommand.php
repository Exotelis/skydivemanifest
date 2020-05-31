<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Class CreateAdminUserCommand
 * @package App\Console\Commands
 */
class CreateAdminUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin
        {--dob= : Date of birth of the user in the format YYYY.MM.DD}
        {--email= : The email address of the user}
        {--firstname= : The firstname of the user}
        {--gender= : The gender of the user. Must be one of - m f d u}
        {--lastname= : The lastname of the user}
        {--password= : The password of the user}
        {--username= : The username}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user for the skydivemanifest';

    /**
     * Execute the console command.
     *
     * @throws Exception
     * @return void
     */
    public function handle()
    {
        $firstname = $this->input->getOption('firstname') ?: $this->ask('What is your first name?');
        $lastname = $this->input->getOption('lastname') ?: $this->ask('What is your last name?');
        $email = $this->input->getOption('email') ?: $this->ask('What is your email address?');

        do {
            $username = $this->input->getOption('username') ?? $this->ask('What is the username?');
            $this->input->setOption('username', null);
            $valid = preg_match('/^[a-z0-9]+$/i', $username);
            $this->printInvalidInfo($valid);
        } while(! $valid);

        do {
            $password = $this->input->getOption('password') ?? $this->secret('What is the password?');
            $this->input->setOption('password', null);
            $valid = preg_match(regexWeakPassword(), $password);
            $this->printInvalidInfo($valid);
        } while(! $valid);

        do {
            $dob = $this->input->getOption('dob') ?? $this->ask('What is your birthday?');
            $this->input->setOption('dob', null);

            try {
                $date = Carbon::parse($dob);
                $dob = $date->toDateString();
                $valid = true;

                if($date->isFuture()) {
                    $valid = false;
                }
            } catch (Exception $exception) {
                $valid = false;
            }

            $this->printInvalidInfo($valid);
        } while(! $valid);

        do {
            $gender = $this->input->getOption('gender') ??
                $this->choice(
                    'What is your gender?',
                    ['m' => 'male', 'f' => 'female', 'd' => 'diverse', 'u' => 'unknown']
                );
            $this->input->setOption('gender', null);
        } while(! in_array($gender, ['m','f','d','u']));

        $user = new User();
        $user->dob = $dob;
        $user->email = $email;
        $user->email_verified_at = Carbon::now();
        $user->firstname = $firstname;
        $user->gender = $gender;
        $user->lastname = $lastname;
        $user->password = $password;
        $user->role_id = adminRole();
        $user->username = $username;
        $user->save();

        Log::info(sprintf('Administrator \'%s|%s\' created successfully', $username, $email));
        $this->info(sprintf('Administrator %s created successfully', $username));
    }

    /**
     * Print info message that the input was invalid
     *
     * @param $valid
     */
    protected function printInvalidInfo($valid)
    {
        $valid ?: $this->info(' Invalid format. Please try again.');
    }
}
