<?
//use Yii;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;
?>

<p>
    <img src="<?=$user->getUserPicture()?>" alt="">
</p>
<?if($user->isCurrentUser()):
    Modal::begin([
        'header' => '<h2>Avatar Upload</h2>',
        'toggleButton' => [
            'label' => 'Edit avatar',
            'class' => 'btn btn-info',
        ],
    ]);
    echo FileUpload::widget([
        'model' => $modelPicture,
        'attribute' => 'picture',
        'url' => ['/user/profile/picture-upload', 'id' => $user->id], // your url, this is just for demo purposes,
        'options' => ['accept' => 'image/*'],
        'clientOptions' => [
            'maxFileSize' => 2000000
        ],
        // Also, you can specify jQuery-File-Upload events
        // see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
        'clientEvents' => [
            'fileuploaddone' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
            'fileuploadfail' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
        ],
    ]);
    Modal::end();
endif;?>
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
