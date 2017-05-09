<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
	use Searchable;
    protected $table = "channels";
    protected $fillable = ['name', 'slug', 'description', 'image_filename'];

    public function user()
    {
      return $this->belongsTo(User::class);
    }

	public function getRouteKeyName()
	{
		return 'slug';
	}

	public function videos()
	{
		return $this->hasMany(Video::class, 'channel_id', 'id');
	}

	public function getImage()
	{
		$basepath = config('codetube.buckets.images') . "cover/";
		return (!$this->image_filename) ? $basepath . "default.png" : $basepath . $this->image_filename;
	}

	public function subscriptions()
	{
		return $this->hasMany(Subscription::class);
	}

	public function subscriptionCount()
	{
		return $this->subscriptions->count();
	}

	public function views()
	{
		return $this->hasManyThrough(VideoView::class, Video::class);
	}

	public function totalViews()
	{
		return $this->views()->count();
	}
}
