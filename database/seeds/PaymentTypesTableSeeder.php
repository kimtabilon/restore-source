<?php

use Illuminate\Database\Seeder;

class PaymentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $types = [
				'Cash',
				'Credit',
                'Debit',
				'Item Donation',
				'Internal Transfer',
    	];

    	foreach($types as $t)
    	{
    		factory(App\PaymentType::class)->create(['name' => $t]);
    	}
    }
}
