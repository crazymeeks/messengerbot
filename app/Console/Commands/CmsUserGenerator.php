<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Console\Commands\CmsUserManager;

class CmsUserGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messengerbot:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to create admin user for cms of messenger bot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $firstname = $this->ask('Enter firstname');
        $lastname = $this->ask('Enter lastname');
        $email = $this->ask('Enter email address');
        $username = $this->ask('Enter username');

        $password = $this->secret('Enter password');


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email');
        } else {

            try {

                $cmsUser = new CmsUserManager();
                $cmsUser->createUser([
                    'firstname' => $firstname,
                    'lastname'  => $lastname,
                    'email' => $email,
                    'username' => $username,
                    'password' => bcrypt($password)
                ]);
                $this->info("User successfully created. Login to /backend/auth/login");
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        }

    }
}
