<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Requests\VideoUpdateRequest;
use App\Http\Requests\VideoCreateRequest;

class VideoController extends Controller
{
	public function index(Request $request)
	{
		$videos = $request->user()->videos()->latestFirst()->paginate(10);

		return view('videos.index', compact('videos'));
	}

	public function show(Video $video)
	{
		return view('videos.show', compact('video'));
	}

	public function edit(Video $video)
	{
		$this->authorize('edit', $video);

		return view('videos.edit', compact('video'));
	}

	public function update(VideoUpdateRequest $request, Video $video)
	{
		$this->authorize('update', $video);

		$video->update([
			'title' => $request->title,
			'caption' => $request->caption,
			'visibility' => $request->visibility,
			'allow_votes' => (int)$request->allow_votes,
			'allow_comments' => (int)$request->allow_comments
		]);

		return $request->ajax() ? response()->json(null, 200) : redirect()->back();
	}

    public function store(VideoCreateRequest $request)
    {
    	# generate uuid
		$uuid = uniqid( true );

		# grab the user's channel
		$channel = $request->user()->channel()->first();

		# create new video in channel
		$video = $channel->videos()->create([
			'uuid' => $uuid,
			'title' => $request->title,
			'caption' => $request->caption,
			'visibility' => $request->visibility,
			'enc_video_filename' => "{$uuid}.{$request->extension}"
		]);

		return response()->json([
			'uuid' => $uuid
		]);
    }

	public function delete(Video $video)
	{
		# authorize

		$video->delete();

		return redirect()->back();
	}
}
