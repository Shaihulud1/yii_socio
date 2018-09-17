<?php

namespace frontend\models;

use Yii;
use frontend\models\User;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $user_id
 * @property string $picture
 * @property string $description
 * @property string $created_at
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'picture' => 'Picture',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }

    public function getPostImage()
    {
        return $this->picture && file_exists(Yii::getAlias('@web').'upload/images/posts/'.$this->picture) ?
        Yii::getAlias('@postsPictureFolder').'/'.$this->picture :
        false;
    }

    public function getCreatedUserData()
    {
        return User::findOne($this->user_id);
    }

    public function checkLikeDislike($type, $user_id)
    {
        $redis = Yii::$app->redis;
        return $redis->sismember("post:{$this->id}:".$type."", $user_id);
    }

    public function addLikeDislike($type, $user_id)
    {
        $redis = Yii::$app->redis;
        return $redis->sadd("post:{$this->id}:".$type."", $user_id);
    }

    public function deleteLikeDislike($type, $user_id)
    {
        $redis = Yii::$app->redis;
        return $redis->srem("post:{$this->id}:".$type."", $user_id);
    }

    public function getLikesAndDislikes($user_id = false)
    {
        $redis = Yii::$app->redis;
        $likeKey = "post:{$this->id}:like";
        $dislikeKey = "post:{$this->id}:dislike";
        $dislikes = $redis->smembers($dislikeKey);
        $likes = $redis->smembers($likeKey);
        if($user_id){
            $userVote = false;
            if(in_array($user_id, $dislikes))
                $userVote = 'DISLIKE';
            elseif(in_array($user_id, $likes))
                $userVote = 'LIKE';
        }
        return ['dislike' => $dislikes, 'like' => $likes, "user_vote" => $userVote];
    }

}
