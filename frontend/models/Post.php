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
}
