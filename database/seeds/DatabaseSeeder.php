<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ContactTypesTableSeeder::class);
        $this->call(DonorTypesTableSeeder::class);
        $this->call(DonorsTableSeeder::class);
        $this->call(ItemCodeTypesTableSeeder::class);
        $this->call(ItemStatusTableSeeder::class);
        $this->call(PaymentTypesTableSeeder::class);
        $this->call(TransactionsTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(ProfilePhotosTableSeeder::class);
        $this->call(SecondaryContactsTableSeeder::class);
    }
}
