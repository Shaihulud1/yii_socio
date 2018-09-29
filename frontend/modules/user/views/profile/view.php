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
            'maxFileSize' => 1024 * 2
        ],
        'clientEvents' => [
            'fileuploaddone' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                    if(data.result.success){
                                        $(".result-image-prepare").attr("src", data.result.result);
                                        $(".result-image-prepare").show();
                                        $(".result-image-upload fail").hide();
                                        $(".save-avatar").show();
                                    }else{
                                        $(".result-image-upload fail").html(data.result.result).hide();
                                        $(".result-image-prepare").hide();
                                        $(".result-image-upload fail").show();
                                        $(".save-avatar").hide();
                                    }
                                }',
            /*'fileuploadfail' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',*/
        ],
    ]);?>
    <img src="" class="result-image-prepare" alt="">
    <p class="result-image-upload fail" style="display:none;"></p>
    <a href="<?=Url::to(['/user/profile/set-avatar', 'id' => $user->getId()])?>" class="btn btn-success save-avatar" style="display:none">Save avatar</a>
    <?Modal::end();
endif;
//Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()])?>
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
<?if(!empty($postsData)):?>
    <h2>Posts:</h2>
    <?foreach($postsData as $post):?>
        <p>
            <a href="<?=Url::to(['/post/default/view', 'id' => $post['id']])?>">
                <img src="<?=$post['picture']?>" alt="">
            </a>
        </p>
    <?endforeach;?>
<?endif;?>
