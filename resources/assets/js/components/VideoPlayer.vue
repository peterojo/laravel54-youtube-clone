<template>
	<video
		id="video"
		class="video-js vjs-default-skin vjs-big-play-centered vjs-16-9"
		controls
		preload="auto"
		v-bind:poster="thumbnailUrl"
		data-setup='{"fluid":true, "preload":"auto"}'>
		<source type="video/mp4" v-bind:src="videoUrl"></source>
	</video>
</template>

<script>
	import videojs from 'video.js';
	export default {
		data () {
			return {
				player: null,
				duration: null
			}
		},
		props: {
			videoUuid: null,
			videoUrl: null,
			thumbnailUrl: null
		},
		methods: {
			hasHitQuotaView () {
				if(!this.duration) {
					return false;
				}

				return Math.round(this.player.currentTime()) === Math.round(0.1 * this.duration);
			},
			createView () {
				axios.post('/videos/' + this.videoUuid + '/views');
			}
		},
		mounted () {
			this.player = videojs('video');
			this.player.on('loadedmetadata', () => {
				this.duration = Math.round(this.player.duration());
			});
			setInterval(() => {
				if(this.hasHitQuotaView()) {
					this.createView();
				}
			}, 1000);
		}
	}
</script>

<style lang="css">

</style>
