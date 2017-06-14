<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Category::class, 10)->create()->each(function ($c) {
        	factory(App\Item::class, 10)->create( [ 'category_id' => $c->id ] )->each(function ($i) {
        		factory(App\Inventory::class)->create( [ 'item_id' => $i->id ] );
                
                $itemCodePrefix = [ '', 'RS-', 'RF-', 'QR-'];
                for($x=1; $x<=3; $x++) {
                    DB::table('item_codes')->insert([
                        'code' => $itemCodePrefix[$x].str_random(10),
                        'item_code_type_id' => $x,
                        'item_id' => $i->id,
                        'created_at' => \Carbon\Carbon::now(), 
                        'updated_at' => \Carbon\Carbon::now(),
                    ]);
                }

        	});
        });
    }
}
