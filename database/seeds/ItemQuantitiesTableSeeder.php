<?php

use Illuminate\Database\Seeder;

class ItemQuantitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($x=1; $x<=100; $x++)
    	{
    		factory(App\ItemQuantity::class)->create(['inventory_id'=>$x]);
    	}
    }
}
