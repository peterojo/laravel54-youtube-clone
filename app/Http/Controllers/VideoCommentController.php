<?php

namespace App\Http\Controllers;

use Fractal;
use App\Models\Video;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Transformers\CommentTransformer;
use App\Http\Requests\CreateVideoCommentRequest;

class VideoCommentController extends Controller
{
    public function index(Video $video)
	{
		return response()->json(
			Fractal::create()->collection($video->comments()->latestFirst()->get())
				->parseIncludes(['replies', 'replies.channel'])
				->transformWith(new CommentTransformer)
				->toArray(),
			200
		);
	}

	public function create(CreateVideoCommentRequest $request, Video $video)
	{
		// authorize
		$this->authorize('comment', $video);

		// create comment
		$comment = $video->comments()->create([
			'body' => $request->body,
			'reply_id' => $request->get('reply_id', null),
			'user_id' => $request->user()->id
		]);

		return response()->json(
			fractal()->item($comment)->parseIncludes(['replies'])->transformWith(new CommentTransformer)->toArray(), //using fractal() helper instead of Fractal facade
			200
		);
	}

	public function delete (Video $video, Comment $comment)
	{
		$this->authorize('delete', $comment);

		$comment->delete();

		return response()->json(null, 200);
	}
}
