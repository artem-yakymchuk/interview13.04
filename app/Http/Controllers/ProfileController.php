<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use JWTAuth;

class ProfileController extends Controller
{
    /**
		* Audience parameters.
		* @param $oprions, $user, $userId;
	*/

	public $user, $userId, $token;

	/**
		* Audience __construct.
	*/

	public function __construct(Request $request)
	{
		try {
			$this->token 		= $request->header('token');
			$this->user 		= JWTAuth::authenticate($this->token);
			$this->userId 		= $this->user->id;
		} catch (\Exception $e) {}
  	}

  	public function getUserProfileById(Request $request, User $userModel, $id)
  	{
  		$userData = $userModel->getUserById($id);        

        return response()->json($userData);
  	}
}
