<?php

use Illuminate\Database\Seeder;

class ItemCodeTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\ItemCodeType::class)->create(['name'=>'Barcode']);
        factory(App\ItemCodeType::class)->create(['name'=>'RF Code']);
        factory(App\ItemCodeType::class)->create(['name'=>'QR Code']);
    }
}
