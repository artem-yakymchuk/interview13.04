<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Point;
use App\Models\Blog;
use App\Models\User;
use JWTAuth;

class BlogController extends Controller
{
    /**
		* Blog parameters.
		* @param $user, $userId;
	*/

	public $user, $userId, $token;

	/**
		* Blog __construct.
	*/

	public function __construct(Request $request)
	{
		try {
			$this->token        = $request->header('token');
            $this->user         = JWTAuth::authenticate($this->token);
            $this->userId       = $this->user->id;
		} catch (\Exception $e) {}
  	}

    public function createBlog(Request $request, Blog $blogModel)
    {
    	$user_id = $request->get('user_id');
    	$name_sity = $request->get('name_sity');
        //TODO добавить нормальное название города
        // TODO добавить поле google data в миграцию
    	if(empty($name_sity)){
    		return response(['message' => 'Enter name sity'], 401);
    	}

    	$blogId = $blogModel->createBlog($user_id, $name_sity);

    	return response()->json($blogId); 
    }

    public function insertPointImg(Request $request, Point $pointModel, $id)
    {
		$image = $request->file('img');

		$image = $this->uploadImage($image);

		$pointModel->updateImgBlogPoint($id, $image);
    }

    /**
		* Upload image.
		* @param $file
		* @return url to image
	*/

  	public function uploadImage($image)
  	{
        $validator = $this->validatorImage(['file' => $image]);

		if($validator->fails())
			return response(['errors' => $validator->errors()], 400);

		$imageNewName = time().rand(1,100).'.'.$image->getClientOriginalExtension();

		$path = public_path() . '/blogs_img';

	    $image->move($path, $imageNewName);

	    return $imageNewName;
  	}

  	/**
		* Validation upload image.
		* @param array
		* @return Error
	*/

	protected function validatorImage(array $data)
    {
		$messages = array(
	        'file.image'	=> 'The file must be an image!',
	        'file.max'		=> 'Maximum 2048 characters!',
	        'file.mimes'  	=> 'Bad format file!',
      	);
			$validatorArray = [
				'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			];

		return Validator::make($data, $validatorArray, $messages);
    }

    public function createBlogPoint(Request $request, Point $pointModel, Blog $blogModel, $id)
    {
    	$lat = $request->get('lat');
    	$lng = $request->get('lng');
    	$title = $request->get('title');
    	$description = $request->get('description');
    	$google_data = $request->get('google_data');
        \Log::info($request->all());
    	if(empty($title)){
    		return response(['message' => 'Enter title'], 401);
    	}

    	if(empty($description)){
    		return response(['message' => 'Enter description'], 401);
    	}
    	$images = [];
        foreach ($request->images as $image){
    	    $images[] = $this->uploadImage($image);
        }
    	$pointModel->createBlogPoint($id, $lat, $lng, $title, $description, $images, $google_data);

    	$blogData = $blogModel->getBlogMargePoints($id);

    	return response()->json($blogData);
    }

    public function getBlogById(Request $request, Blog $blogModel, $id)
    {
    	$blogData = $blogModel->getBlogMargePoints($id);

    	return response()->json($blogData);
    }

    public function getBlogs(Request $request, Blog $blogModel)
    {
    	$blogsData = $blogModel->getBlogs();

    	return response()->json($blogsData);
    }

    public function getUserBlog(Request $request, Blog $blogModel, $id)
    {
    	$userBlogsData = $blogModel->getUserBlog($id);

    	return response()->json($userBlogsData);
    }

    public function getSearchBlogs(Request $request, Blog $blogModel) {
    	$ser = $request->get('s');
        if( !empty($ser) ) {
            $BlogsData = $blogModel->getSearchBlogs($ser);

            return response()->json($BlogsData);
        } else {
            return response()->json();
        }	
    }
    public function finishBlog(Request $request){
        Blog::where('id',$request->id)->update(['finished'=>1]);
        return response()->json(true);
    }
    public function getLastBlog(Request $request){
        try{
            return Blog::with(['points', 'user'])->where([['finished',0],['user_id',$this->userId]])->get()->first();
        }catch (\Exception $e){
            return null;
        }

    }
}
