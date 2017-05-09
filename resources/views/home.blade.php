@extends('layouts.app')

@section('content')
	<div class="container">
	    <div class="row">
	        <div class="col-md-8 col-md-offset-2">
	            <div class="panel panel-default">
	                <div class="panel-heading">Dashboard</div>

	                <div class="panel-body">
						@forelse($videos as $video)
							<div class="well">
								@include('videos.partials._video_result', ['video'=>$video])
							</div>
						@empty
							<p>There are no videos to display</p>
						@endforelse
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@endsection
