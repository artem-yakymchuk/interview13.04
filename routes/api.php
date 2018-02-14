<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['api']], function () {

	Route::post('/auth/sign_up',
    [
      'as'    => 'sign-up',
      'uses'  => 'Auth\RegisterController@signUp'
    ]);

    Route::post('/auth/sign_in',
	    [
	      'as'    => 'sign-in',
	      'uses'  => 'Auth\LoginController@signIn'
	    ]);

});

Route::group(['middleware' => ['api', 'jwt.auth']], function () {

	Route::get('/profile/{id}',
	    [
	      'as'    => 'user-id',
	      'uses'  => 'ProfileController@getUserProfileById'
	    ]);

	Route::post('/profile/edit/{id}',
	    [
	      'as'    => 'user-id',
	      'uses'  => 'ProfileController@editUserProfile'
	    ]);

	Route::post('/blog/create',
	    [
	      'as'    => 'create-blog',
	      'uses'  => 'BlogController@createBlog'
	    ]);

	Route::post('/blog/add_point/{id}',
	    [
	      'as'    => 'create-blog-point',
	      'uses'  => 'BlogController@createBlogPoint'
	    ]);

	Route::post('/blog/point/{id}/img',
	    [
	      'as'    => 'point-img',
	      'uses'  => 'BlogController@insertPointImg'
	    ]);

	Route::get('/blog/{id}',
	    [
	      'as'    => 'blog-id',
	      'uses'  => 'BlogController@getBlogById'
	    ]);

	Route::get('/blogs/all',
	    [
	      'as'    => 'blogs-all',
	      'uses'  => 'BlogController@getBlogs'
	    ]);

	Route::get('/blogs/user/{id}',
	    [
	      'as'    => 'blogs-userId',
	      'uses'  => 'BlogController@getUserBlog'
	    ]);

	Route::get('/blogs/search',
	    [
	      'as'    => 'blogs-search',
	      'uses'  => 'BlogController@getSearchBlogs'
	    ]);

    Route::post('/blog/finish',
        [
            'as'    => 'blogs-finish',
            'uses'  => 'BlogController@finishBlog'
        ]);
    Route::post('/blogs/last',
        [
            'as'    => 'blogs-last',
            'uses'  => 'BlogController@getLastBlog'
        ]);

});