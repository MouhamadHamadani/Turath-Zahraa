<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * The admin seeder is last: it refuses to run without ADMIN_EMAIL and
     * ADMIN_PASSWORD, and the content seeders should not be blocked by that.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            MilestoneSeeder::class,
            PageSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
