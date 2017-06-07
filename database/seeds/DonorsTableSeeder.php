<?php

use Illuminate\Database\Seeder;

class DonorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($type=1; $type<=3; $type++)
        {
            factory(App\Donor::class, 10)->create( ['donor_type_id' => $type] )->each(function ($u) {
                $u->profile()->save(factory(App\Profile::class)->make());
                $u->storeCredits()
                    ->saveMany([
                        factory(App\StoreCredit::class)->make(),
                        factory(App\StoreCredit::class)->make(),
                        factory(App\StoreCredit::class)->make(),
                    ]);
            });
        }
    }
}
