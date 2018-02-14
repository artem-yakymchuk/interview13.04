<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'img', 'country', 'birthday', 'about', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getUserById($id)
    {
        $user = User::find($id)->toArray();

        return $user;
    }

    public function getUserByEmail($email)
    {
        $user = User::where('email', $email)->first()->toArray();

        return $user;
    }
}
