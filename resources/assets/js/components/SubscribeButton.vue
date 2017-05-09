<template>
	<div v-if="subscribers !== null">
		{{ pluralize('subscriber', subscribers, true) }} &nbsp; <button class="btn btn-xs btn-danger" v-if="canSubscribe" @click.prevent="handle">{{ userSubscribed ? 'Unsubscribe' : 'Subscribe' }}</button>
	</div>
</template>

<script>
import pluralize from 'pluralize';
export default {
	props: {
		channelSlug: null
	},
	data () {
		return {
			pluralize: null,
			subscribers: null,
			userSubscribed: false,
			canSubscribe: false
		}
	},
	methods: {
		getSubscriptionStatus () {
			axios.get('/subscription/' + this.channelSlug).then((response) => {
				this.userSubscribed = response.data.data.user_subscribed;
				this.canSubscribe = response.data.data.can_subscribe;
				this.subscribers = response.data.data.count;
			});
		},
		subscribe () {
			this.userSubscribed = true;
			this.subscribers++;

			axios.post('/subscription/' + this.channelSlug);
		},
		unsubscribe () {
			this.userSubscribed = false;
			this.subscribers--;

			axios.delete('/subscription/' + this.channelSlug);
		},
		handle () {
			if (this.userSubscribed) {
				this.unsubscribe();
			} else {
				this.subscribe();
			}
		}
	},
	mounted () {
		this.pluralize = pluralize;
		this.getSubscriptionStatus();
	}
}
</script>
