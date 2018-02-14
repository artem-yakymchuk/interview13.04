<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'name_sity', 'finished', 'photo'
    ];

    public function getBlogs()
    {
    	$blogs = Blog::with(['points', 'user'])->get()->makeHidden('created_at')->makeHidden('updated_at');
            // Todo возвращать создателя
        return $blogs;
    }

    public function getBlogById($id)
    {
    	$blog = Blog::with(['points', 'user'])->find($id)->toArray();

        return $blog;
    }

    public function getUserBlog($id)
    {
    	$blogs = Blog::with(['points', 'user'])->where('user_id', $id)->get()->makeHidden('created_at')->makeHidden('updated_at');

        return $blogs;
    }

    public function getBlogMargePoints($id)
    {
    	$data = Blog::with(['points', 'user'])->find($id)->makeHidden('created_at')->makeHidden('updated_at');

    	return $data;
    }

    public function getSearchBlogs($ser)
    {
    	$data = Blog::with(['points', 'user'])->where('name_sity', 'like', $ser)->get()->makeHidden('created_at')->makeHidden('updated_at');

    	return $data;
    }

	public function createBlog($user_id, $name_sity)
	{
		$blogId = Blog::create([
									'user_id'	=> $user_id,
									'name_sity'	=> $name_sity,
                                    'photo'=>""
								])->id;
		return $blogId;
	}

	public function points()
    {
      return $this->hasMany(Point::class, 'id_blog');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
