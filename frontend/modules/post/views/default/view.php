<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
?>
<p>
    <?
    print_r($arDislikeLike);
    if($picture = $post->getPostImage()):?>
        <img src="<?=$picture?>" alt="">
    <?endif;?>
    <p><?=HtmlPurifier::process($post->description)?></p>
    <?$user = $post->getCreatedUserData();?>
    <p>created by <a href="<?=Url::to(['/user/profile/view', 'nickname' => $user->getNickNameUrl()])?>"><?=$user->username?></a> <?=date('d-m-Y', $post->created_at);?></p>
    <a href="#" class="btn btn-primary button-vote like-btn <?=$arDislikeLike['user_vote'] == 'LIKE' ? 'btn-success' : ''?>" data-id="<?=$post->id?>" data-type="LIKE">Like<?=count($arDislikeLike['like']) > 0 ? '('.count($arDislikeLike['like']).')' : ''?></a>
    <a href="#" class="btn btn-primary button-vote dislike-btn <?=$arDislikeLike['user_vote'] == 'DISLIKE' ? 'btn-danger' : ''?>" data-id="<?=$post->id?>" data-type="DISLIKE">Dislike<?=count($arDislikeLike['dislike']) > 0 ? '('.count($arDislikeLike['dislike']).')' : ''?></a>
</p>
<img src="" alt="">
