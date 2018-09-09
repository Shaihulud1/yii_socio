<?php
namespace frontend\modules\user\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use frontend\models\User;
use frontend\modules\user\models\forms\PictureForm;
use dosamigos\fileupload\FileUpload;
use yii\web\UploadedFile;


class ProfileController extends Controller
{
    public function actionView($nickname)
    {
        $userInfo = $this->findUser($nickname);
        $modelPicture = new PictureForm;

        return $this->render('view', [
            'user' => $userInfo,
            'modelPicture' => $modelPicture,
        ]);
    }

    public function actionSubscribe($id)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/user/default/login']);
        }
        $curUser = Yii::$app->user->identity;
        $userTarget = $this->getUserById($id);
        $curUser->followUser($userTarget);
        return $this->redirect(['/user/profile/view', "nickname" => $userTarget->getNickNameUrl()]);
    }

    public function actionUnsubscribe($id)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/user/default/login']);
        }
        $curUser = Yii::$app->user->identity;
        $userTarget = $this->getUserById($id);
        $curUser->unFollowUser($userTarget);
        return $this->redirect(['/user/profile/view', "nickname" => $userTarget->getNickNameUrl()]);
    }

    private function getUserById($id)
    {
        if($result = User::findOne($id))
            return $result;
        throw new NotFoundHttpException();
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

    public function actionPictureUpload()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        if($request->isPost && !empty($id)){
            $modelPicture = new PictureForm();
            $filePicture = UploadedFile::getInstance($modelPicture, 'picture');
            $saveResult = $modelPicture->savePrepareResult($filePicture, $id);
        }
    }

}
