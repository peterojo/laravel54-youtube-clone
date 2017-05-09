<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoView extends Model
{
    protected $table = "video_views";

	protected $fillable = ['user_id', 'video_id', 'ip'];

	public function scopeByUser($query, User $user)
	{
		return $query->where('user_id', $user->id);
	}

	public function scopeLatestByUser($query, User $user)
	{
		return $query->byUser($user)->orderBy('created_at', 'DESC')->take(1);
	}

	public function scopeLatestByIp($query, $ip)
	{
		return $query->where('ip', $ip)->orderBy('created_at', 'DESC')->take(1);
	}
}
