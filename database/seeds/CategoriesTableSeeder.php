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
        /*factory(App\Category::class, 10)->create()->each(function ($c) {
        	factory(App\Item::class, 10)->create( [ 'category_id' => $c->id ] )->each(function ($i) {

                factory(App\ItemCode::class)
                    ->create([ 
                            'item_id'           => $i->id, 
                            'item_code_type_id' => 1, 
                            'code'              => 'RS-'.str_random(5), 
                        ]);


                factory(App\ItemCode::class)
                    ->create([ 
                            'item_id'           => $i->id, 
                            'item_code_type_id' => 2, 
                            'code'              => 'RF-'.str_random(5), 
                        ]);
                    
                factory(App\ItemCode::class)
                    ->create([ 
                            'item_id'           => $i->id, 
                            'item_code_type_id' => 3, 
                            'code'              => 'QR-'.str_random(5), 
                        ]);

        		$inventoy = factory(App\Inventory::class)->create([ 'item_id' => $i->id ]);

                $inventoy->itemDiscounts()  ->attach(1);
                $inventoy->itemImages()     ->attach(rand(1, 5));
                $inventoy->itemPrices()     ->attach(rand(1, 5));
                $inventoy->donors()         ->attach(rand(1, 30));
                $inventoy->transactions()   ->attach(rand(1, 30));
        	});
        });*/
    }
}
