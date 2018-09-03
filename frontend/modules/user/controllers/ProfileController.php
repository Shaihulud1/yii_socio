<?php
namespace frontend\modules\user\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use frontend\models\User;

class ProfileController extends Controller
{
    public function actionView($nickname)
    {
      $userInfo = $this->findUser($nickname);
      return $this->render('view', [
        'user' => $userInfo,
      ]);
    }

    private function findUser($nickname)
    {
      $result = User::find()
                ->where(['nickname' => $nickname])
                ->orWhere(['id' => $nickname])
                ->one();
      if(!empty($result))
        return $result;
      throw new NotFoundHttpException();
    }
}
