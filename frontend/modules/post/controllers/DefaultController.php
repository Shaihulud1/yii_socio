<?php

namespace frontend\modules\post\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\web\Response;
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
        $user_id = false;
        if(!Yii::$app->user->isGuest){
            $user_id = Yii::$app->user->identity->id;
        }
        return $this->render('view', [
            'post' => $postModel,
            'arDislikeLike' => $postModel->getLikesAndDislikes($user_id),
        ]);
    }

    public function actionVote()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/user/default/login']);
        }
        $request = Yii::$app->request;
        if($request->isPost){
            $user = Yii::$app->user->identity;
            $targetPost = Post::findOne($request->post('postId'));

            switch ($request->post('type')) {
                case 'LIKE':
                    if($targetPost->checkLikeDislike('like', $user->id)){//delete like
                        $targetPost->deleteLikeDislike('like', $user->id);
                    }else{//add like
                        if($targetPost->checkLikeDislike('dislike', $user->id))//delete dislike
                            $targetPost->deleteLikeDislike('dislike', $user->id);
                        $targetPost->addLikeDislike('like', $user->id);
                    }
                break;

                case 'DISLIKE':
                    if($targetPost->checkLikeDislike('dislike', $user->id)){//delete dislike
                        $targetPost->deleteLikeDislike('dislike', $user->id);
                    }else{//add dislike
                        if($targetPost->checkLikeDislike('like', $user->id))//delete like
                            $targetPost->deleteLikeDislike('like', $user->id);
                        $targetPost->addLikeDislike('dislike', $user->id);
                    }
                break;
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $targetPost->getLikesAndDislikes($user->id);
        }


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
