<?php

namespace App\Http\Controllers\Api;

use Auth;
use Image;
use \App\ItemImage;
use \App\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Storage;

class ItemImageController extends Controller
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
		return [
			'images' => ItemImage::orderBy('name')->get(),
			'items'  => Item::orderBy('name')->get(),
		]; 	
	}	

	public function create(Request $request)
	{
		// return $request->file('image');
		// return $request->input('image_name');
		// return $request->file('image_file');

        $file = ItemImage::create([
            'name' 			=> $request->input('image_name'),
            'description' 	=> $request->input('image_description'),
            'type' 			=> $request->file('image_file')->extension(),
            'size' 			=> $request->file('image_file')->getClientSize(),
        ]);

 		$image_path 	= 'images/items';

 		$saved_image 	= $file->id . '.' . $file->type;

        $request->file('image_file')->move($image_path, $saved_image);

        $thumbnail = Image::open($image_path.'/'.$saved_image)->thumbnail(new \Imagine\Image\Box(50,50));
		// $thumbnail->effects()->grayscale();
		$thumbnail->save($image_path.'/'.$file->id.'_thumb.jpg');
 
        return response()->json(['errors' => [], 'files' => ItemImage::all(), 'status' => 200], 200);
	}

	public function destroy(Request $request)
    {
    	$image = ItemImage::find($request->input('id'));
        $image->delete();
        @unlink(public_path().'\images\items\\' . $image->id .'.'.$image->type);
        @unlink(public_path().'\images\items\\' . $image->id .'_thumb.jpg');
 
        return response()->json(['errors' => [], 'message' => 'File Successfully deleted!', 'status' => 200], 200);
    	
    }
}
