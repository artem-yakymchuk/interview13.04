<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_blog', 'lat', 'lng', 'title', 'description', 'img', 'google_data'
    ];

    protected $hidden = [
      'created_at',
      'updated_at'
    ];

	public function getImgAttribute($value)
	{
		if(!$value){
			return null;
		}else{
		    $images = explode(',',$value);
		    $result = [];
		    foreach ($images as $image){
		        $result[]= url('blogs_img') .'/'.$image;
            }
			return $result;
		}
	}

    public function createBlogPoint($id_blog, $lat, $lng, $title, $description,$images, $google_data)
	{
		$blogId = Point::create([
									'id_blog'		=> $id_blog,
									'lat'			=> $lat,
									'lng'			=> $lng,
									'title'			=> $title,
									'description'	=> $description,
                                    'img'           =>implode(',', $images),
                                    'google_data'   => $google_data
								])->id;

		return $blogId;
	}

	public function updateImgBlogPoint($id, $image)
	{
		Point::where('id', $id)
				->update([
							'img' => $image
						]);
	}
}
