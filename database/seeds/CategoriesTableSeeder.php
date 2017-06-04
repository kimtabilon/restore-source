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
        		factory(App\ItemCode::class)->create( [ 'item_id' => $i->id ] );
        		factory(App\Inventory::class)->create( [ 'item_id' => $i->id ] );
        	});
        });
    }
}
