<template>
	<div class="container">
	    <div class="row">
	        <div class="col-md-8 col-md-offset-2">
	            <div class="panel panel-default">
	                <div class="panel-heading">Upload</div>

	                <div class="panel-body">
	                    <input type="file" name="video" id="video" class="form-control" @change="fileInputChange" v-if="!uploading">

						<div class="alert alert-danger" v-if="failed">Something went wrong. Please try again.</div>

						<div id="video-form" v-if="uploading && !failed">
							<div class="alert alert-info" v-if="!uploadingComplete">
								Your video will be available at <a v-bind:href="$root.baseUrl+'videos/'+uuid">{{ $root.baseUrl + 'videos/' + uuid }}</a>
							</div>
							<div class="alert alert-success" v-if="uploadingComplete">
								Upload complete! Video is now processing. <a v-bind:href="$root.baseUrl+'videos'">Go to your videos</a>
							</div>
							<div class="progress" v-if="!uploadingComplete">
								<div class="progress-bar progress-bar-striped active" v-bind:style="{ width: fileProgress + '%' }">{{ fileProgress+'%' }}</div>
							</div>
							<div class="form-group">
								<label class="form-label">Title:</label>
								<input type="text" class="form-control" onfocus="this.select()" autofocus v-model="title">
							</div>
							<div class="form-group">
								<label class="form-label">Caption:</label>
								<textarea class="form-control" v-model="caption"></textarea>
							</div>
							<div class="form-group">
								<label class="form-label">Visibility:</label>
								<select class="form-control" v-model="visibility">
									<option value="public">Public</option>
									<option value="private">Private</option>
									<option value="unlisted">Unlisted</option>
								</select>
							</div>
							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" v-model="allow_votes" value="yes" aria-label="Votes"> Allow Votes
									</label>
								</div>
							</div>
							<div class="form-group">
								<div class="checkbox">
									<label>
										<input type="checkbox" v-model="allow_comments" value="yes" aria-label="Comments"> Allow Comments
									</label>
								</div>
							</div>
							<span class="help-block pull-right">{{ saveStatus }}</span>
							<button type="submit" class="btn btn-default" @click.prevent="update">Save Changes</button>
						</div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script>
    export default {
		data() {
			return {
				uuid: null,
				uploading: false,
				uploadingComplete: false,
				failed: false,
				title: 'Untitled',
				caption: null,
				visibility: 'private',
				saveStatus: null,
				fileProgress: 0,
				allow_votes: false,
				allow_comments: true
			}
		},
		methods: {
			fileInputChange() {
				this.uploading = true;
				this.failed = false;

				this.file = document.getElementById('video').files[0];

				// store video metadata
				this.store().then(() => {
					// upload video
					var form = new FormData();
					form.append('video', this.file);
					form.append('uuid', this.uuid);

					axios.post('/upload', form, {
						onUploadProgress: (e) => {
							this.fileProgress = Math.round( (e.loaded * 100) / e.total )
						}
					}).then(() => {
						this.uploadingComplete = true;
					}, () => {
						this.failed = true;
						// @todo delete stored record for the video
					});
				}, () => {
					this.failed = true;
					// @todo delete stored record for the video
				});


			},
			store() {
				// store video metadata
				return axios.post('/videos', {
					title: this.title,
					caption: this.caption,
					visibility: this.visibility,
					extension: this.file.name.split('.').pop()
				})
				.then((response) => {
					this.uuid = response.data.uuid;
				});
			},
			update() {
				this.saveStatus = 'Saving changes...';
				return axios.put('/videos/'+this.uuid, {
					title: this.title,
					caption: this.caption,
					visibility: this.visibility,
					allow_votes: this.allow_votes,
					allow_comments: this.allow_comments
				}).then((response) => {
					this.saveStatus = 'Changes saved.';

					setTimeout(() => {
						this.saveStatus = null;
					}, 3000);
				}).catch((error) => {
					this.saveStatus = 'Failed to save changes because '+error.toString();
				});
			}
		},
        mounted() {
            window.onbeforeunload = () => {
				if (this.uploading && !this.uploadingComplete && !this.failed) {
					return 'Your video is not done uploading. Are you sure you want to go away?';
				}
			}
        }
    }
</script>
