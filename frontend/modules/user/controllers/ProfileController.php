<?php
namespace frontend\modules\user\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use frontend\models\User;

class ProfileController extends Controller
{
    public function actionView($id)
    {
      $userInfo = $this->findUser($id);
      return $this->render('view', [
        'user' => $userInfo,
      ]);
    }

    private function findUser($id)
    {
      $result = User::find()->where(['id' => $id])-> one();
      if(!empty($result))
        return $result;
      throw new NotFoundHttpException();
    }
}
