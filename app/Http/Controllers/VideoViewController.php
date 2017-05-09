<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoViewController extends Controller
{
	const BUFFER = 30;

    public function create(Request $request, Video $video)
	{
		if(!$video->canBeAccessed($request->user()))
			return;

		// grab last view for user
		if($request->user()) {
			$lastUserView = $video->views()->latestByUser($request->user())->first();

			// check if in buffer, return if too soon
			if($this->withinBuffer($lastUserView))
				return;
		}
		//grab last view for current ip address
		else {
			// do same
			$lastIpView = $video->views()->latestByIp($request->ip())->first();

			if($this->withinBuffer($lastIpView))
				return;
		}


		$video->views()->create([
			'user_id' => $request->user() ? $request->user()->id : null,
			'ip' => $request->ip()
		]);

		return response()->json(null, 200);
	}

	protected function withinBuffer($view)
	{
		return $view && $view->created_at->diffInSeconds(Carbon::now()) < self::BUFFER;
	}
}
