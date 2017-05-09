<template>
	<div class="video__voting">
		<a href="#"
		class="video__voting-button"
		v-bind:class="{'video__voting-button--voted': userVote=='up'}"
		@click.prevent="vote('up')">
			<span class="glyphicon glyphicon-thumbs-up"></span>
		</a> {{ up }} &nbsp;
		<a href="#"
		class="video__voting-button"
		v-bind:class="{'video__voting-button--voted': userVote=='down'}"
		@click.prevent="vote('down')">
			<span class="glyphicon glyphicon-thumbs-down"></span>
		</a> {{ down }}
	</div>
</template>

<script>
export default {
	data () {
		return {
			up: null,
			down: null,
			userVote: null,
			canVote: false
		}
	},
	methods: {
		getVotes () {
			axios.get('/videos/'+this.videoUuid+'/votes').then((response)=>{
				this.up = response.data.up;
				this.down = response.data.down;
				this.canVote = response.data.can_vote;
				this.userVote = response.data.user_vote;
			});
		},
		vote (type) {
			if (this.userVote == type) {
				this[type]--; //decrement this.up or this.down
				this.userVote = null;
				this.deleteVote(type);
				return;
			}

			if (this.userVote) {
				this[type=='up'?'down':'up']--;
				//this.deleteVote(type=='up'?'down':'up'); this is done on the server-side
			}

			this[type]++;
			this.userVote = type;

			this.createVote(type);
		},
		deleteVote (type) {
			axios.delete('/videos/'+this.videoUuid+'/votes');
		},
		createVote (type) {
			axios.post('/videos/'+this.videoUuid+'/votes', {
				type: type
			});
		}
	},
	props: {
		videoUuid: null
	},
	mounted () {
		this.getVotes();
	}
}
</script>

<style lang="css">
</style>
