<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
	use SoftDeletes, Searchable, Traits\Voteable, Traits\Orderable;

	protected $dates = ['deleted_at'];

    protected $fillable = [
		'channel_id',
		'uuid',
		'title',
		'caption',
		'processed',
		'enc_video_id',
		'enc_video_filename',
		'visibility',
		'allow_votes',
		'allow_comments',
		'processed_percentage',
	];

	public function toSearchableArray()
	{
		$properties = $this->toArray();
		$properties['visible'] = $this->isProcessed() && $this->isPublic();

		return $properties;
	}

	public function channel()
	{
		return $this->belongsTo(Channel::class , 'channel_id', 'id');
	}

	public function getRouteKeyName()
	{
		return 'uuid';
	}

	public function isProcessed()
	{
		return $this->processed;
	}

	public function getThumbnail()
	{
		if (!$this->isProcessed()) {
			return config('codetube.buckets.videos')."default.jpg";
		} else {
			return config('codetube.buckets.videos').$this->enc_video_id."_1.jpg";
		}
	}

	public function processedPercentage()
	{
		return $this->processed_percentage;
	}

	public function votesAllowed()
	{
		return (bool)$this->allow_votes;
	}

	public function commentsAllowed()
	{
		return (bool)$this->allow_comments;
	}

	public function isPrivate()
	{
		return $this->visibility === "private";
	}

	public function isPublic()
	{
		return $this->visibility === "public";
	}

	public function ownerByUser(User $user)
	{
		return $this->channel->user->id == $user->id;
	}

	public function canBeAccessed($user = null)
	{
		if (!$user && $this->isPrivate())
			return false;

		if ($user && $this->isPrivate() && !$this->ownerByUser($user))
			return false;

		return true;
	}

	public function getStreamUrl()
	{
		return config('codetube.buckets.videos') . $this->enc_video_id . ".mp4";
	}

	public function views()
	{
		return $this->hasMany(VideoView::class);
	}

	public function viewCount()
	{
		return $this->views->count();
	}

	public function votes()
	{
		return $this->morphMany(Vote::class, 'voteable');
	}

	public function voteFromUser(User $user)
	{
		return $this->votes->where('user_id', $user->id);
	}

	public function comments()
	{
		return $this->morphMany(Comment::class, 'commentable')->where('reply_id', null);
	}

	public function scopeProcessed($query)
	{
		return $query->where('processed', true);
	}

	public function scopePublic($query)
	{
		return $query->where('visibility', 'public');
	}

	public function scopeVisible($query)
	{
		return $query->processed()->public();
	}
}
