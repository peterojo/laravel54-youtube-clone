<template>
	<div>
		<p>{{ pluralize('comment', comments.length, true) }}</p>
		<div class="video-comment clearfix" v-if="$root.user.authenticated">
			<textarea class="form-control video-comment__input" placeholder="Say something" v-model="body"></textarea>
			<div class="pull-right">
				<button type="submit" class="btn btn-default" @click.prevent="createComment">Post</button>
			</div>
		</div>
		<ul class="media-list">
			<li class="media" v-for="comment in comments">
				<div class="media-left">
					<a v-bind:href="'/channel/' + comment.channel.data.slug">
						<img v-bind:src="comment.channel.data.image" v-bind:alt="comment.channel.data.name + ' image'" class="media-object">
					</a>
				</div>
				<div class="media-body">
					<a v-bind:href="'/channel/' + comment.channel.data.slug">{{ comment.channel.data.name }}</a> {{ comment.created_at_human }}
					<p>{{ comment.body }}</p>
					<ul class="list-inline" v-if="$root.user.authenticated">
						<li><a href="#" @click.prevent="toggleReplyForm(comment.id)">{{ replyFormVisible == comment.id ? 'Cancel' : 'Reply' }}</a></li>
						<li><a href="#" v-if="$root.user.id === comment.user_id" @click.prevent="deleteComment(comment.id)">Delete</a></li>
					</ul>
					<div class="video-comment clear" v-if="replyFormVisible === comment.id">
						<textarea class="form-control video-comment__input" v-model="replyBody"></textarea>
						<div class="pull-right">
							<button type="submit" class="btn btn-default" @click.prevent="createReply(comment.id)">Reply</button>
						</div>
					</div>
					<ul class="media-list">
						<li class="media" v-for="reply in comment.replies.data">
							<div class="media-left">
								<a v-bind:href="'/channel/' + reply.channel.data.slug">
									<img v-bind:src="reply.channel.data.image" v-bind:alt="reply.channel.data.name + ' image'" class="media-object">
								</a>
							</div>
							<div class="media-body">
								<a v-bind:href="'/channel/' + reply.channel.data.slug">{{ reply.channel.data.name }}</a> {{ reply.created_at_human }}
								<p>{{ reply.body }}</p>
								<ul class="list-inline" v-if="$root.user.authenticated">
									<li><a href="#" v-if="$root.user.id === reply.user_id" @click.prevent="deleteComment(reply.id)">Delete</a></li>
								</ul>
							</div>
						</li>
					</ul>
				</div>
			</li>
		</ul>
	</div>
</template>

<script>
import pluralize from 'pluralize';
export default {
	props: {
		videoUuid: null
	},
	data () {
		return {
			pluralize: null,
			comments: [],
			body: null,
			replyBody: null,
			replyFormVisible: null
		}
	},
	methods: {
		createComment () {
			axios.post('/videos/' + this.videoUuid + '/comments', { body: this.body }).then((response) => {

				this.comments.unshift(response.data.data);
				this.body = null;
			});
		},
		createReply (commentId) {
			axios.post('/videos/' + this.videoUuid + '/comments', { body: this.replyBody, reply_id: commentId }).then((response) => {
				this.comments.map((comment, index) => {
					if (comment.id === commentId) {
						this.comments[index].replies.data.push(response.data.data);
						return;
					}
				});
				this.replyBody = null;
				this.replyFormVisible = null;
			});
		},
		toggleReplyForm (commentId) {
			this.replyBody = null;

			if (this.replyFormVisible == commentId) {
				this.replyFormVisible = null;
				return;
			}

			this.replyFormVisible = commentId;
		},
		getComments () {
			axios.get('/videos/' + this.videoUuid + '/comments').then((response) => {
				this.comments = response.data.data;
			});
		},
		deleteComment (commentId) {
			if (!confirm('Are you sure you want to delete this comment?')) {
				return;
			}

			this.deleteById(commentId);
			axios.delete('/videos/' + this.videoUuid + '/comments/' + commentId);
		},
		deleteById (commentId) {
			this.comments.map((comment, index) => {
				if (comment.id === commentId) {
					this.comments.splice(index, 1);
					return;
				}

				comment.replies.data.map((reply, replyIndex) => {
					if (reply.id === commentId) {
						this.comments[index].replies.data.splice(replyIndex, 1);
						return;
					}
				});
			});
		}
	},
	mounted () {
		this.pluralize = pluralize;
		this.getComments();
	}
}
</script>
