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
        for($role=1; $role<=3; $role++)
        {
            factory(App\User::class, 2)->create( ['role_id' => $role] )->each(function ($u) {
                $u->photos()->save(factory(App\UserPhoto::class)->make());
                $u->logs()
                    ->saveMany([
                        factory(App\UserLog::class)->make(),
                        factory(App\UserLog::class)->make(),
                        factory(App\UserLog::class)->make(),
                    ]);
            });
        }

    }
}
