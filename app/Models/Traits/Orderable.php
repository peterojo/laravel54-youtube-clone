<?php

namespace App\Models\Traits;

trait Orderable {
	
	public function scopeLatestFirst($query)
	{
		return $query->orderBy('created_at', 'DESC');
	}
}
