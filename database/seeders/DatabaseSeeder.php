<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'admin',
            'password' => bcrypt('secret'),
            'name' => 'Admin Room',
            'level' => 'admin'
        ]);

        $this->call([
            ExtraChangeSeeder::class
        ]);
        // \App\Models\User::factory(500)->create();
    }
}
