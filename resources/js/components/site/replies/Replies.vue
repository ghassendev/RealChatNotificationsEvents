<template>
    <v-container class="forum-replies-wrapper">
        <h2 class="forum-replies-title">All Replies About <span class="forum-color-primary">{{title}}</span></h2>
        <reply v-for="(reply, index) in allReplies" :key="reply.id" :reply="reply" :index="index"
               v-if="showReply"></reply>
        <create-reply v-else></create-reply>
    </v-container>
</template>

<script>
    import Reply from "./Reply";
    import CreateReply from "./CreateReply";

    export default {
        name: "Replies",
        props: ["question", "title"],
        components: {CreateReply, Reply},
        data() {
            return {
                allReplies: this.question.replies,
                showReply: true
            }
        },
        created() {
            this.loadReplies()
        },
        methods: {
            loadReplies() {
                EventBus.$on('newReply', (reply) => {
                    this.allReplies.unshift(reply);
                });
                EventBus.$on('destroyReply', (index) => {
                    axios.delete(`/api/question/${this.question.slug}/reply/${this.allReplies[index].id}`)
                        .then((response) => {
                            this.allReplies.splice(index, 1);
                        })
                        .catch((error) => console.log(error.response.data))
                });
                Echo.private('App.User.' + User.getIdUser())
                    .notification((notification) => {
                        this.allReplies.unshift(notification.reply);
                    });
                Echo.channel('DeleteReplyChannel').listen('DeleteReplyEvent', (e) => {
                    this.allReplies.forEach((reply) => {
                        if(reply.id === e.id) {
                            this.allReplies.splice(reply, 1)
                        }
                    })
                })
            }
        }
    }
</script>

<style scoped>

</style>
