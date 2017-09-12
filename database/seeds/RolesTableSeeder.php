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
        $cashier    = factory(App\Role::class)->create(['name' => 'Cashier']);
        $rc         = factory(App\Role::class)->create(['name' => 'Receiving Coordinator']);
        $manager    = factory(App\Role::class)->create(['name' => 'Manager']);
        
        factory(App\User::class)
            ->create([
                'role_id'       => $manager->id,
                'given_name'    => 'Francis',
                'middle_name'   => '',
                'last_name'     => 'Macatulad',
                'email'         => 'francis.macatulad@habitat.org.ph',
                ]);
            
        factory(App\User::class)
            ->create([
                'role_id'       => $rc->id,
                'given_name'    => 'Jericho',
                'middle_name'   => '',
                'last_name'     => 'Vasquez',
                'email'         => 'jericho.vasquez@habitat.org.ph',
                ]);
                
        factory(App\User::class)
            ->create([
                'role_id'       => $cashier->id,
                'given_name'    => 'Kem Robert',
                'middle_name'   => '',
                'last_name'     => 'Tan',
                'email'         => 'kemrobert.tan@habitat.org.ph',
                ]);            
    }
}
