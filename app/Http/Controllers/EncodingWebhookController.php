<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Video;
use Illuminate\Http\Request;

class EncodingWebhookController extends Controller
{
    public function handle(Request $request)
	{
		$event = camel_case($request->event);

		if (method_exists($this, $event))
		{
			$this->{$event}($request);
		}
	}

	protected function videoCreated(Request $request)
	{

	}

	protected function videoEncoded(Request $request)
	{
		// lookup the video
		$video = $this->getVideoByFilename($request->original_filename);

		// update the processed column
		$video->processed = true;
		$video->enc_video_id = $request->encoding_ids[0];

		$video->save();
	}

	protected function encodingProgress(Request $request)
	{
		// lookup the video
		$video = $this->getVideoByFilename($request->original_filename);

		// update the processed_percentage
		$video->processed_percentage = $request->progress;
		
		$video->save();
	}

	protected function encodingComplete(Request $request)
	{

	}

	protected function getVideoByFilename($filename)
	{
		return Video::where('enc_video_filename', $filename)->firstOrFail();
	}
}
