<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class MakeAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-admin {--email= : Admin email address} {--name= : Admin full name} {--password= : Admin password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or promote a user to administrator';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->option('email') ?: text(
            label: 'Administrator email address',
            placeholder: 'admin@dzuelsfoundation.org',
            required: true,
            validate: fn ($val) => filter_var($val, FILTER_VALIDATE_EMAIL) ? null : 'Please enter a valid email address.'
        );

        $name = $this->option('name') ?: text(
            label: 'Administrator full name',
            placeholder: 'Foundation Admin',
            required: true
        );

        $plainPassword = $this->option('password') ?: password(
            label: 'Administrator password',
            required: true,
            validate: fn ($val) => strlen($val) >= 8 ? null : 'Password must be at least 8 characters.'
        );

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($plainPassword),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $this->info("✓ Administrator account for [{$user->email}] successfully configured.");

        return self::SUCCESS;
    }
}
