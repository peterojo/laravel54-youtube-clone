@extends('layouts.app')

@section('content')
	<div class="container">
	    <div class="row">
	        <div class="col-md-8 col-md-offset-2">
	            <div class="panel panel-default">
	                <div class="panel-heading">Videos</div>

	                <div class="panel-body">
	                    @forelse($videos as $video)
							<div class="well">
								<div class="row">
									<div class="col-sm-3">
										<a href="{{ url('videos/'.$video->uuid) }}">
											<img src="{{ $video->getThumbnail() }}" alt="" class="img-responsive">
										</a>
									</div>
									<div class="col-sm-9">
										<a href="{{ url('videos/'.$video->uuid) }}">{{ $video->title }}</a>
										<div class="row">
											<div class="col-sm-6">
												@if(!$video->isProcessed())
													<small class="text-muted">Processing... ({{ $video->processedPercentage() ? $video->processedPercentage()."%" : 'Starting up' }})</small>
												@else
													<span class="text-muted">{{ $video->created_at->toFormattedDateString() }}</span>
												@endif
												<p>{{ str_limit($video->caption, 35) }}</p>
												<form action="{{ url('videos/'.$video->uuid) }}" method="post">
													<a href="{{ url('videos/'.$video->uuid."/edit") }}" class="btn btn-default">Edit</a>
													<button type="submit" class="btn btn-danger">Delete</button>
													{{ csrf_field() }}
													{{ method_field('DELETE') }}
												</form>

											</div>
											<div class="col-sm-6">
												likes
											</div>
										</div>
									</div>
								</div>
							</div>
							{{ $videos->links() }}
						@empty
							<p>You have no videos.</p>
						@endforelse
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@stop
