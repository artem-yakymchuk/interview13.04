<?php

namespace App\Http\Controllers\Auth;

use JWTAuth;
use App\Models\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function signUp(Request $request, User $userModel)
    {
        $email   = $request->get('email');
        $name = $request->get('name');
        $password = $request->get('password');
        $country = $request->get('country');
        $birthday = $request->get('birthday');

        $validatorArray = [
            'email' => $email
            ,'name' => $name
            ,'password' => $password
            ,'birthday' => $birthday
            ,'country' => $country
        ];

        $validator = $this->validator($validatorArray);

        if($validator->fails())
            return response(['message' => $validator->errors()], 401);

        $createUser = User::create([
                                        'name' => $name,
                                        'email' => $email,
                                        'country' => $country,
                                        'birthday' => $birthday,
                                        'password' => bcrypt($password)
                                    ])->id;

        if (! $token = JWTAuth::attempt($validatorArray)) {
            return response()->json(['message' => 'invalid_credentials'], 401);
        }

        $userData = $userModel->getUserById($createUser);
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
            'name.required'       => 'Enter name!',
            'email.required'      => 'We need to know your e-mail address!',
            'email.unique'        => 'E-mail address is busy!',
            'password.required'   => 'Enter password!',
            'password.min'        => 'Minimum 8 characters!',
        ];

        $validatorArray = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'country' => 'required|string',
            'birthday' => 'required|date',
            'password' => 'required|string|min:8',
        ];

        return Validator::make($data, $validatorArray, $messages);
    }
}
