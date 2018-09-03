<?
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>

<p>Username: <?=Html::encode($user->username)?></p>
<p>Email: <?=Html::encode($user->email)?></p>
<?if($user->nickname):?>
    <p>Nickname: <?=Html::encode($user->nickname)?></p>
<?endif;?>
<?if($user->about):?>
    <p>Info: <?=HtmlPurifier::process($user->about)?></p>
<?endif;?>
