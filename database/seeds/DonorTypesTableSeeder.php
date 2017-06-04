<?php

use Illuminate\Database\Seeder;

class DonorTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\DonorType::class)->create(['name' => 'Company']);
        factory(App\DonorType::class)->create(['name' => 'Individual']);
        factory(App\DonorType::class)->create(['name' => 'Customer']);
    }
}
