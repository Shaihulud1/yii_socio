<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
?>
<p>
    <?if($picture = $post->getPostImage()):?>
        <img src="<?=$picture?>" alt="">
    <?endif;?>
    <p><?=HtmlPurifier::process($post->description)?></p>
    <?$user = $post->getCreatedUserData();?>
    <p>created by <a href="<?=Url::to(['/user/profile/view', 'nickname' => $user->getNickNameUrl()])?>"><?=$user->username?></a> <?=date('d-m-Y', $post->created_at);?></p>
    <div id="app-vote">
        <vote-likes axi-url="/post/default/vote"
                    count-likes="<?=count($arDislikeLike['like'])?>"
                    count-dislikes="<?=count($arDislikeLike['dislike'])?>"
                    user-like="<?=$arDislikeLike['user_vote'] == 'LIKE' ? true : false?>"
                    user-dislike="<?=$arDislikeLike['user_vote'] == 'DISLIKE' ? true : false?>"
                    user-vote="active"
                    post-id="<?=$post->id?>">
        </vote-likes>
    </div>
</p>
<img src="" alt="">
