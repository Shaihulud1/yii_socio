<?php
namespace frontend\controllers;

use yii\web\Controller;
use frontend\models\User;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $arUsers = User::find()->all();
        return $this->render('index', [
          'arUsers' => $arUsers,
        ]);
    }

}
