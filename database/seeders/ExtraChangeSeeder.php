<?php

namespace Database\Seeders;

use App\Models\ExtraChange;
use Illuminate\Database\Seeder;

class ExtraChangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExtraChange::create([
            'name' => 'Minuman Soda',
            'price' => 20000
        ]);

        ExtraChange::create([
            'name' => 'Air Putih',
            'price' => 15000
        ]);

        ExtraChange::create([
            'name' => 'Jasa Laundry',
            'price' => 100000
        ]);

        ExtraChange::create([
            'name' => 'Snack',
            'price' => 25000
        ]);
    }
}
