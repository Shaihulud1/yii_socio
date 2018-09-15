<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
?>
<p>
    <?
    if($picture = $post->getPostImage()):?>
        <img src="<?=$picture?>" alt="">
    <?endif;?>
    <p><?=HtmlPurifier::process($post->description)?></p>
    <?$user = $post->getCreatedUserData();?>
    <p>created by <a href="<?=Url::to(['/user/profile/view', 'nickname' => $user->getNickNameUrl()])?>"><?=$user->username?></a> <?=date('d-m-Y', $post->created_at);?></p>
</p>
<img src="" alt="">
