<?php
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">
        <div class="row">
            <div class="users-wrapper" style="text-align:center;">
              <h2>USERS:</h2>
              <div class="col-lg-12">
                  <?foreach($arUsers as $user):?>
                      <div class="user-item">
                          <h3><?=$user->username?>(<?=$user->email?>)</h3>
                          <a href="<?=Url::to(['/user/profile/view', 'nickname' => $user->getNickNameUrl()])?>">Перейти в профиль</a>
                      </div>
                      <hr>
                  <?endforeach;?>
              </div>
            </div>
        </div>
    </div>
</div>
