<?php

use Illuminate\Database\Seeder;
use \Faker\Factory;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Role::class)->create(['name' => 'Cashier']);
        factory(App\Role::class)->create(['name' => 'Receiving Coordinator']);
        $manager = factory(App\Role::class)->create(['name' => 'Manager']);
        factory(App\User::class)->create(['role_id' => $manager->id]);
    }
}
