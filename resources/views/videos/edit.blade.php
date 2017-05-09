@extends('layouts.app')

@section('content')
	<div class="container">
	    <div class="row">
	        <div class="col-md-8 col-md-offset-2">
	            <div class="panel panel-default">
	                <div class="panel-heading">Edit video: <em>{{ $video->title }}</em></div>

	                <div class="panel-body">
	                    <form class="form-group" action="{{ url('videos/'.$video->uuid) }}" method="post">
							<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
	                            <label for="title" class="control-label">Title</label>
	                            <input id="title" type="text" class="form-control" name="title" value="{{ old('title', $video->title) }}" required>
	                            @if ($errors->has('title'))
	                                <span class="help-block">
	                                    <strong>{{ $errors->first('title') }}</strong>
	                                </span>
	                            @endif
	                        </div>
							<div class="form-group{{ $errors->has('caption') ? ' has-error' : '' }}">
	                            <label for="caption" class="control-label">Caption</label>
	                            <textarea name="caption" class="form-control">{{ e(old('caption', $video->caption)) }}</textarea>
	                            @if ($errors->has('caption'))
	                                <span class="help-block">
	                                    <strong>{{ $errors->first('caption') }}</strong>
	                                </span>
	                            @endif
	                        </div>
							<div class="form-group{{ $errors->has('visibility') ? ' has-error' : '' }}">
	                            <label for="caption" class="control-label">Visibility</label>
	                            <select class="form-control" name="visibility">
	                            	@foreach(['public', 'private', 'unlisted'] as $visibility)
										<option value="{{ $visibility }}"{{ $visibility==$video->visibility ? " selected" : "" }}>{{ ucfirst($visibility) }}</option>
									@endforeach
	                            </select>
	                            @if ($errors->has('visibility'))
	                                <span class="help-block">
	                                    <strong>{{ $errors->first('visibility') }}</strong>
	                                </span>
	                            @endif
	                        </div>
							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="allow_votes" value="1"{{ $video->votesAllowed() ? ' checked' : '' }}> Allow Votes
									</label>
								</div>
							</div>
							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="allow_comments" value="1"{{ $video->commentsAllowed() ? ' checked' : '' }}> Allow Comments
									</label>
								</div>
							</div>
							{{ csrf_field() }}
							{{ method_field('PUT') }}
							<button type="submit" class="btn btn-primary">Update</button>
	                    </form>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
@stop
