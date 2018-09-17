$('.button-vote').on('click', function(){
    var type = $(this).data('type'),
        postId = $(this).data('id');
    $.ajax({
      url: "/post/default/vote",
      method: "POST",
      data:{type:type,
            postId:postId},
    }).done(function(data) {
        $('.like-btn').text(data.like.length > 0 ? 'Like('+data.like.length+')' : 'Like');
        $('.dislike-btn').text(data.dislike.length > 0 ? 'Dislike('+data.dislike.length+')' : 'Dislike');
        $('.dislike-btn').removeClass('btn-danger');
        $('.like-btn').removeClass('btn-success');
      switch (data.user_vote) {
          case "DISLIKE":
            $('.dislike-btn').addClass('btn-danger');
          break;

          case "LIKE":
            $('.like-btn').addClass('btn-success');
          break;
      }
    });
    return false;
});
