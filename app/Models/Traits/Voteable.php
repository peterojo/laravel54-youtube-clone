<?php

namespace App\Models\Traits;

trait Voteable {
	public function upVotes()
	{
		return $this->votes->where('type', 'up');
	}

	public function downVotes()
	{
		return $this->votes->where('type', 'down');
	}
}
