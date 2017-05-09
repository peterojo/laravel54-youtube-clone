<div class="row">
	<div class="col-sm-3">
		<a href="/videos/{{ $video->uuid }}">
			<img src="{{ $video->getThumbnail() }}" alt="{{ $video->title }} thumbnail" class="img-responsive">
		</a>
	</div>
	<div class="col-sm-9">
		<a href="/videos/{{ $video->uuid }}">{{ $video->title }}</a>
		@if($video->caption)
			<p>{{ $video->caption }}</p>
		@else
			<p class="text-muted">No caption</p>
		@endif

		<ul class="list-inline">
			<li class="list-item">
				<a href="/channel/{{ $video->channel->slug }}">{{ $video->channel->name }}</a>
			</li>
			<li class="list-item">{{ $video->created_at->diffForHumans() }}</li>
			<li class="list-item">{{ $video->viewCount() }} {{ str_plural('view', $video->viewCount()) }}</li>
		</ul>
	</div>
</div>
