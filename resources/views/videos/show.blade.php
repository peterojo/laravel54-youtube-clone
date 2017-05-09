@extends('layouts.app')

@section('content')
	<div class="container">
	    <div class="row">
	        <div class="col-md-8 col-md-offset-2">
				@if ($video->isPrivate() && Auth::check() && $video->ownerByUser(Auth::user()))
					<div class="alert alert-info">Your video is currently private. Only you can see it.</div>
				@endif
				@if ($video->isProcessed() && $video->canBeAccessed(Auth::user()))
					<video-player
						video-uuid="{{ $video->uuid }}"
						video-url="{{ $video->getStreamUrl() }}"
						thumbnail-url="{{ $video->getThumbnail() }}"></video-player>
				@endif
				@if (!$video->isProcessed())
					<div class="video-placeholder">
						<div class="video-placeholder__header">
							This video is processing. Come back a little bit later.
						</div>
					</div>
				@elseif (!$video->canBeAccessed(Auth::user()))
					<div class="video-placeholder">
						<div class="video-placeholder__header">
							This video is private.
						</div>
					</div>
				@endif
	            <div class="panel panel-default">
	                <div class="panel-body">
	                    <h4>{{ $video->title }}</h4>
						<div class="pull-right">
							<div class="video__views">
								{{ $video->viewCount() }} {{ str_plural('view', $video->viewCount()) }}
							</div>
							@if ($video->votesAllowed())
								<video-voting video-uuid="{{ $video->uuid }}"></video-voting>
							@endif
						</div>
						<div class="media">
							<div class="media-left">
								<a href="/channel/{{ $video->channel->slug }}">
									<img src="{{ $video->channel->getImage() }}" alt="{{ $video->channel->name }}" class="media-object">
								</a>
							</div>
							<div class="media-body">
								<a href="/channel/{{ $video->channel->slug }}" class="media-heading">
									{{ $video->channel->name }}
								</a><br>
								<subscribe-button channel-slug="{{ $video->channel->slug }}"></subscribe-button>
							</div>
						</div>
	                </div>
	            </div>
				@if($video->caption)
					<div class="panel panel-default">
		                <div class="panel-body">
		                    {!! nl2br(e($video->caption)) !!}
		                </div>
		            </div>
				@endif

				<div class="panel panel-default">
					<div class="panel-body">
						@if (!$video->canBeAccessed(Auth::user()))
							<p>We won't show you the comments on a private video.</p>
						@elseif (!$video->commentsAllowed())
							<p>Comments are disable for this video</p>
						@else
							<video-comments video-uuid="{{ $video->uuid }}"></video-comments>
						@endif
					</div>
				</div>
	        </div>
	    </div>
	</div>
@endsection
