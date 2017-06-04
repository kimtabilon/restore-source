<?php

use Illuminate\Database\Seeder;

class ContactTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $types = [
				'Phone Number',
				'Address',
				'Email',
				'Telephone Number',
				'Fax',
    	];

    	foreach($types as $t)
    	{
    		factory(App\ContactType::class)->create(['name' => $t]);
    	}
    }
}
