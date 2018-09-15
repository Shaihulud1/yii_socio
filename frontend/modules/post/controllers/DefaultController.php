<?php

namespace frontend\modules\post\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use frontend\models\Post;
use frontend\modules\post\models\forms\PostForm;


class DefaultController extends Controller
{
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest){
            return $this->goHome();
        }
        $user = Yii::$app->user->identity;
        $postForm = new PostForm($user);
        if($postForm->load(Yii::$app->request->post())){
            $postForm->picture = UploadedFile::getInstance($postForm, 'picture');
            if($postForm->save()){
                Yii::$app->session->setFlash('success', 'Post created');
                return $this->redirect(['/user/profile/view', "nickname" => $user->getNickNameUrl()]);
            }
        }
        return $this->render('create', [
            'model' => $postForm,
        ]);
    }

    public function actionView($id)
    {
        $postModel = $this->findPostById($id);
        return $this->render('view', [
            'post' => $postModel,
        ]);
    }

    private function findPostById($id)
    {
        $post = new Post;
        if($postModel = $post::findOne($id)){
            return $postModel;
        }
        return new NotFoundHttpException();
    }
}
