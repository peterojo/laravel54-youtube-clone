<?php

namespace App\Transformers;

use App\Models\Comment;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
	/**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
		'replies'
    ];

	protected $defaultIncludes = [
		'channel'
	];

	public function transform(Comment $comment)
	{
		return [
			'id' => $comment->id,
			'user_id' => $comment->user->id,
			'body' => $comment->body,
			'created_at' => $comment->created_at->toDateTimeString(),
			'created_at_human' => $comment->created_at->diffForHumans(),
		];
	}

	public function includeChannel(Comment $comment)
	{
		$channel = $comment->user->channel->first();

		return $this->item($channel, new ChannelTransformer);
	}

	public function includeReplies(Comment $comment)
	{
		return $this->collection($comment->replies, new CommentTransformer);
	}
}
