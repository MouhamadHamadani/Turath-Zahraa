<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    /**
     * Credentials come from ADMIN_EMAIL / ADMIN_PASSWORD (see config/app.php).
     * The password is only set when the user is created, so re-running the
     * seeder never overwrites a password changed later.
     */
    public function run(): void
    {
        $email = config('app.admin.email');
        $password = config('app.admin.password');

        if (blank($email) || blank($password)) {
            throw new RuntimeException('Set ADMIN_EMAIL and ADMIN_PASSWORD in .env before seeding the admin user.');
        }

        if (strlen($password) < 12) {
            throw new RuntimeException('ADMIN_PASSWORD must be at least 12 characters.');
        }

        $user = User::firstOrNew(['email' => $email]);

        if (! $user->exists) {
            $user->name = config('app.admin.name');
            $user->password = $password;
            $user->email_verified_at = now();
        }

        $user->is_admin = true;
        $user->save();
    }
}
