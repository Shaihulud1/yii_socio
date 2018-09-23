<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class VueAsset extends AssetBundle
{
    public $sourcePath  = '@frontend/assets';
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/vue/axios.js',
        'js/vue/vue.js',
        'js/vue/likes.js',
    ];
}
