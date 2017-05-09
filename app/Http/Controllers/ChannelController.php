<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function show(Channel $channel)
	{
		return view('channel.show', [
			'channel' => $channel,
			'videos' => $channel->videos()->visible()->latestFirst()->paginate(5)
		]);
	}
}
