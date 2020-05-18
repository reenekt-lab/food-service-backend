<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class UserCreateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new user (administrator) interactively';

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
        $this->warn('BE CAREFUL! All entered information will NOT be validated!');

        $first_name = $this->ask('What is your first name?');
        $surname = $this->ask('What is your surname?');
        $middle_name = $this->ask('What is your middle name?');
        $phone_number = $this->ask('Enter your phone number');
        $email = $this->ask('Enter your email');
        $password = $this->secret('Enter your password');

        $password_hash = Hash::make($password);

        $user = User::create([
            'surname' => $surname,
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'phone_number' => $phone_number,
            'password' => $password_hash,
            'email' => $email,
        ]);

        if ($user) {
            $this->info("Created user with id = {$user->id}");
            return 0;
        } else {
            $this->error('USER WAS NOT CREATED!');
            return 1;
        }
    }
}
