<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // for($role=1; $role<=3; $role++)
        // {
        //     factory(App\User::class, 2)->create( ['role_id' => (int)$role] )->each(function ($u) {
        //         $u->userPhotos()->save(factory(App\UserPhoto::class)->make());
        //         $u->userLogs()
        //             ->saveMany([
        //                 factory(App\UserLog::class)->make(),
        //                 factory(App\UserLog::class)->make(),
        //                 factory(App\UserLog::class)->make(),
        //             ]);
        //     });
        // }

        // factory(App\ItemPrice::class, 5)->create();
        // factory(App\ItemImage::class, 5)->create();
        // factory(App\ItemDiscount::class)->create();

    }
}
