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
                $itemPrice      = factory(App\ItemPrice::class)->create([ 'item_id' => $i->id ]);
                $itemImage      = factory(App\ItemImage::class)->create([ 'item_id' => $i->id ]);
                $itemDiscount   = factory(App\ItemDiscount::class)->create([ 'item_id' => $i->id ]);
                factory(App\ItemCode::class)->create([ 'item_id' => $i->id ]);
        		factory(App\Inventory::class)->create([ 
                                                'item_id'           => $i->id, 
                                                'item_price_id'     => $itemPrice->id, 
                                                'item_image_id'     => $itemImage->id, 
                                                'item_discount_id'  => $itemDiscount->id, 
                                                ]);
        	});
        });
    }
}
