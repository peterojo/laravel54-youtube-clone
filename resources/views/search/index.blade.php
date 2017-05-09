@extends('layouts.app')

@section('content')
	<div class="container">
	    <div class="row">
	        <div class="col-md-8 col-md-offset-2">
	            <div class="panel panel-default">
	                <div class="panel-heading">Search Results for <em>"{{ Request::get('q') }}"</em></div>

	                <div class="panel-body">
	                    @if($channels->count())
							<h4>Channels</h4>
							<div class="well">
								@foreach($channels as $channel)
									<div class="media">
										<div class="media-left">
											<a href="/channel/{{ $channel->slug }}">
												<img src="{{ $channel->getImage() }}" alt="{{ $channel->name }} image" class="media-object">
											</a>
										</div>
										<div class="media-body">
											<a href="/channel/{{ $channel->slug }}" class="media-heading">{{ $channel->name }}</a>
											<ul class="list-inline">
												<li>{{ $channel->subscriptionCount() }} {{ str_plural('subscriber', $channel->subscriptionCount()) }}</li>
											</ul>
										</div>
									</div>
								@endforeach
							</div>
						@endif

						@if($videos->count())
							<h4>Videos</h4>
							@foreach($videos as $video)
								<div class="well">
									@include('videos.partials._video_result', ['video'=>$video])
								</div>
							@endforeach
						@else
							<p>No videos found.</p>
						@endif
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@endsection
