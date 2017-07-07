<?php

namespace App\Http\Controllers\Api;

use Auth;
use \App\Donor;
use \App\DonorType;
use \App\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DonorController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function create(Request $request) 
	{
		$donor = $request->input('donor');
		$donor_type = DonorType::where('name', 'Customer')->get()->first();

		$new_donor = new Donor();

		$new_donor->given_name 	= $donor['given_name'];
		$new_donor->middle_name = $donor['middle_name'];
		$new_donor->last_name 	= $donor['last_name'];
		$new_donor->email 		= $donor['email'];
		$new_donor->donorType()->associate($donor_type->id);
		$new_donor->save();

		$new_profile = new Profile();
		$new_profile->title 	= $donor['profile']['title'];
		$new_profile->phone 	= $donor['profile']['phone'];
		$new_profile->tel 		= $donor['profile']['tel'];
		$new_profile->address 	= $donor['profile']['address'];
		$new_profile->company 	= $donor['profile']['company'];
		$new_profile->job_title = $donor['profile']['job_title'];
		$new_profile->catch_phrase = '';
		$new_profile->donor()->associate($new_donor);
		$new_profile->save();

		return Donor::where('id', $new_donor->id)->with(['profile', 'donorType'])->get()->first();
	}

	public function destroy(Request $request) 
	{
		$donor = $request->input('id');
		Donor::find($donor)->delete();
		return 'Removed!';
	}
}
