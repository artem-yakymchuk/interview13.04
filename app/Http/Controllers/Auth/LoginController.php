<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use JWTAuth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function signIn(Request $request, User $userModel)
    {
        $email   = $request->get('email');
        $password = $request->get('password');

        $validatorArray = ['email' => $email, 'password' => $password];

        $validator = $this->validator($validatorArray);

        if($validator->fails())
            return response(['message' => $validator->errors()], 401);

        if (! $token = JWTAuth::attempt($validatorArray)) {
            return response()->json(['message' => 'invalid_credentials'], 401);
        }


        $userData = $userModel->getUserByEmail($email);
        
        $userData['token'] = $token;
        

        return response()->json($userData);
    }

    /**
        * Get a validator for an incoming registration request.
        *
        * @param  array  $data
        * @return \Illuminate\Contracts\Validation\Validator
    */
    protected function validator(array $data)
    {
        $messages = [
                        'email.required'      => 'We need to know your e-mail address!',
                        'email.exists'        => 'E-mail address is busy!',
                        'password.required'   => 'Enter password!',
                        'password.min'        => 'Minimum 8 characters!',
                    ];

        $validatorArray = [
                            'email' => 'required|string|email|max:255|exists:users',
                            'password' => 'required|string|min:8',
                        ];

        return Validator::make($data, $validatorArray, $messages);
    }
}
