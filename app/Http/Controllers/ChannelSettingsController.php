<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;
use App\Http\Requests\ChannelUpdateRequest;
use App\Jobs\UploadImage;

class ChannelSettingsController extends Controller
{
    public function edit(Channel $channel)
	{
		$this->authorize('edit', $channel);

		return view('channel.settings.edit', compact('channel'));
	}

	public function update(ChannelUpdateRequest $request, Channel $channel)
	{
		$this->authorize('update', $channel);

		$channel->update([
			'name' => $request->name,
			'slug' => $request->slug,
			'description' => $request->description,
		]);

		if ($request->hasFile('image')) {
			# move to temp location
			$request->file('image')->move(storage_path('uploads'), $fileId = uniqid(true));

			# dispatch job
			$this->dispatch(new UploadImage($channel, $fileId)); // dispatch method is available on controllers


		}

		return redirect()->to("/channel/{$channel->slug}/edit");
	}
}
