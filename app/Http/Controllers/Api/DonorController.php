<?php

namespace App\Http\Controllers\Api;

use Auth;
use \App\Donor;
use \App\DonorType;
use \App\Profile;
use \App\StoreCredit;
use \App\ItemCodeType;
use \App\ItemStatus;

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

	public function index()
	{
		$donor_types = DonorType::with([
								'donors', 
								'donors.profile', 
								'donors.donorType', 
								'donors.storeCredits', 
								'donors.inventories', 
								'donors.inventories.transactions', 
								'donors.inventories.item',
								'donors.inventories.itemPrices',
								'donors.inventories.itemSellingPrices',
								'donors.inventories.itemCodes',
								'donors.inventories.itemStatus',
							])
							->orderBy('name')
							->get();
		$code_types 	= ItemCodeType::all();	
		$item_status 	= ItemStatus::all();	

		return [ 'donor_types'=>$donor_types, 'code_types'=>$code_types, 'item_status'=>$item_status ];				
	}

	public function create(Request $request) 
	{
		$donor = $request->input('donor');
		$donor_type = DonorType::where('name', $donor['donor_type'])->get()->first();

		if($donor['id'] != 0) {
			$match_donor = Donor::find($donor['id']);
			$match_donor->given_name 	= $donor['given_name'];
			$match_donor->middle_name 	= $donor['middle_name'];
			$match_donor->last_name 	= $donor['last_name'];
			$match_donor->email 		= $donor['email'];
			$match_donor->donorType()->associate($donor_type->id);
			$match_donor->save();

			$new_donor = $match_donor;

			$match_profile = Profile::find($donor['profile']['id']);
			$match_profile->title 		= $donor['profile']['title'];
			$match_profile->phone 		= $donor['profile']['phone'];
			$match_profile->tel 		= $donor['profile']['tel'];
			$match_profile->address 	= $donor['profile']['address'];
			$match_profile->company 	= $donor['profile']['company'];
			$match_profile->job_title 	= $donor['profile']['job_title'];
			$match_profile->catch_phrase = '';
			$match_profile->save();

			$match_credit = StoreCredit::where('donor_id', $donor['id'])->first();
			$match_credit->amount = $donor['store_credits'][0]['amount'];
			$match_credit->save();
		}
		else {
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

			$new_credit 		= new StoreCredit();
			$new_credit->amount = $donor['store_credits'][0]['amount'];
			$new_credit->donor()->associate($new_donor);
			$new_credit->save();
		}

		return Donor::where('id', $new_donor->id)->with(['profile', 'donorType', 'inventories', 'storeCredits'])->get()->first();
	}

	public function destroy(Request $request) 
	{
		$donor = $request->input('id');
		Donor::find($donor)->delete();
		return 'Removed!';
	}
}
