<?
//use Yii;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
?>
<p>
    <img src="<?=$user->getUserPicture()?>" alt="">
</p>
<?if($user->isCurrentUser()):?>
<?
Modal::begin([
    'header' => '<h2>Avatar Upload</h2>',
    'toggleButton' => [
        'label' => 'Edit avatar',
        'class' => 'btn btn-info',
    ],
]);

echo '  <p>
            <a href="set-image" class="btn btn-info">Choose Image</a>
        </p>';

Modal::end();?>
<?endif;?>
<p>Username: <?=Html::encode($user->username)?></p>
<p>Email: <?=Html::encode($user->email)?></p>
<?if($user->nickname):?>
    <p>Nickname: <?=Html::encode($user->nickname)?></p>
<?endif;?>
<?if($user->about):?>
    <p>Info: <?=HtmlPurifier::process($user->about)?></p>
<?endif;?>
<?if((!$user->isSubscribe() || Yii::$app->user->isGuest) && !$user->isCurrentUser()):?>
    <a href="<?=Url::to(['/user/profile/subscribe', 'id' => $user->getId()])?>" class="btn btn-info">Subscribe</a>
<?elseif(Yii::$app->user->id != $user->id):?>
    <a href="<?=Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()])?>" class="btn btn-info">Unsubscribe</a>
<?endif;?>
<?$arSubscribtions = $user->getSubscriptions();?>
<?if($arSubscribtions):?>
    <h2>Subscribtions:</h2>
    <?foreach($arSubscribtions as $sub):?>
        <p><?=Html::encode($sub['username'])?>(<?=Html::encode($sub['email'])?>)</p>
        <a href="<?=Url::to(['/user/profile/view', 'nickname' => $sub['nickname'] ? $sub['nickname'] : $sub['id']])?>">go to Profile</a>
    <?endforeach;?>
<?endif;?>
<?$arFollowers = $user->getFollowers();?>
<?if($arFollowers):?>
    <h2>Followers:</h2>
    <?foreach($arFollowers as $follower):?>
        <p><?=Html::encode($follower['username'])?>(<?=Html::encode($follower['email'])?>)</p>
        <a href="<?=Url::to(['/user/profile/view', 'nickname' => $follower['nickname'] ? $follower['nickname'] : $follower['id']])?>">go to Profile</a>
    <?endforeach;?>
<?endif;?>
