<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\UploadVideo;

class VideoUploadController extends Controller
{
    public function index()
	{
		return view('videos.upload');
	}

	public function store(Request $request)
	{
		# grab user channel
		$channel = $request->user()->channel()->first();

		# lookup the video
		$video = $channel->videos()->where('uuid', $request->uuid)->firstOrFail();
		
		# move to temp location
		if ($request->hasFile('video')) {
			$request->file('video')->move(storage_path('uploads'), $video->enc_video_filename);
		}

		# upload to s3
		$this->dispatch(new UploadVideo($video->enc_video_filename));

		return response()->json(null, 200);
	}
}
