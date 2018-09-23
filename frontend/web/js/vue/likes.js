Vue.component('vote-likes', {
    template: '\
        <div class="js-like-dis">\
            <a href="#" class="btn btn-primary button-vote like-btn" v-bind:class="{\'btn-success\': vote.likeActive}" v-on:click.prevent="sendVote(vote.likeType)">Likes {{ vote.likes }}</a>\
            <a href="#" class="btn btn-primary button-vote dislike-btn" v-bind:class="{\'btn-success\': vote.dislikeActive}" v-on:click.prevent="sendVote(vote.dislikeType)">Dislikes {{ vote.dislikes }}</a>\
        </div>\
    ',
    props: ['axiUrl', 'userVote', 'postId', 'countDislikes', 'countLikes', 'userLike', 'userDislike'],
    data: function () {
      return {
        vote: {
            likeType: 'LIKE',
            dislikeType: 'DISLIKE',
            likes: this.countLikes,
            dislikes: this.countDislikes,
            likeActive: this.userLike,
            dislikeActive: this.userDislike,
        },
      }
    },
    methods: {
        sendVote: function(typeVote){
            let vm = this;
            $.post(this.axiUrl, {
                type:typeVote,
                postId:this.postId,
            }).done(function (response) {
                vm.vote.likes = response.like.length;
                vm.vote.dislikes = response.dislike.length;
                switch (response.user_vote) {
                    case "LIKE":
                        vm.vote.likeActive = true;
                        vm.vote.dislikeActive = false;
                    break;

                    case "DISLIKE":
                        vm.vote.dislikeActive = true;
                        vm.vote.likeActive = false;
                    break;
                }
            });
        },
    },
});
if(document.getElementById('app-vote') != null){
    vm = new Vue({
        el: '#app-vote',
    });
}
