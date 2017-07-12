<?php

namespace App\Http\Controllers\Api;

use Auth;
use \App\User;
use \App\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
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
		// $manager = Role::where('name', 'Manager')->first();
		$users = User::with([
						'role',
					])
					// ->where('role_id', '!=', $manager->id)
					// ->orderBy('role_id')
					->get();
		$roles = Role::orderBy('name')->get();
		
		return [ 'users'=>$users, 'roles'=>$roles ];				
	}

	public function profile()
	{
		return User::with(['role'])->where('id', Auth::user()->id)->first();
	}

	public function save(Request $request)
	{
		$data = $request->all();
		$role = Role::where('name', $data['role'])->first();
		
		switch ($data['action']) {
			case 'new':
				$user = new User();
				if($data['password']=='')
				{
					$user->password = bcrypt('secret');
				}
				else {
					$user->password = bcrypt($data['password']);
				}
				break;

			case 'edit':
				$user = User::find($data['id']);
				if($data['password']!='')
				{
					$user->password = bcrypt($data['password']);
				}
				break;

			default:
				break;	
		}	

		$user->given_name  = $data['given_name'];
		$user->middle_name = $data['middle_name'];
		$user->last_name   = $data['last_name'];
		$user->username    = $data['username'];
		$user->email       = $data['email'];
		$user->role()->associate($role->id);
		$user->save();

		return User::where('id', $user->id)->with('role')->first();		
	}

	public function destroy(Request $request)
	{
		$data = $request->all();
		$user = User::find($data['id']);
		$user->delete();
		return 'Removed!';
	}
}
