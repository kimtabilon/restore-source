<?php

use Illuminate\Database\Seeder;

class ItemStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$status = [
				'For Review',
				'Under Repair',
				'For Approval',
				'Good',
				'For Disposal',
				'For Transfer',
				'Transferred',
				'Disposed',
				'Sold',
				'Refunded',
				'Returned',
    	];

    	foreach($status as $s)
    	{
    		factory(App\ItemStatus::class)->create(['name' => $s]);
    	}
    }
}
