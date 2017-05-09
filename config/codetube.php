<?php

return [
	'buckets' => [
		'videos' => 'https://s3.amazonaws.com/'.env('VIDEO_BUCKET').'/',
		'images' => 'https://s3.amazonaws.com/'.env('IMAGE_BUCKET').'/',
	]
];
