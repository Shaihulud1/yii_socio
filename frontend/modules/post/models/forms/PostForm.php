<?php

namespace frontend\modules\post\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\Post;
use frontend\models\User;
//yii::$app->params['maxFileSize'];

class PostForm extends Model
{
    const DESC_LENGHT = 1000;
    const MAX_PICTURE_SIZE = 1024 * 1024 * 2;//2mb

    public $picture;
    public $description;

    private $user;

    public function rules()
    {
        return [
            [['picture'], 'file',
                'skipOnEmpty' => true,
                'extensions' => ['jpg', 'png'],
                'checkExtensionByMimeType' => true,
                'message' => 'Wrong extension',
                'maxSize' => self::MAX_PICTURE_SIZE,
            ],
            [['description'],
                'string',
                'max' => self::DESC_LENGHT
            ],
        ];
    }

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function save()
    {
        if($this->validate()){
            $postModel = new Post();

            $pictureName = strtolower(md5(uniqid($user->id.$user->username))).'.'.$this->picture->extension;
            $this->picture->saveAs(Yii::getAlias('@web').'upload/images/posts/'.$pictureName);
            $postModel->picture = $pictureName;

            $postModel->description = $this->description;
            $postModel->created_at = time();
            $postModel->user_id = $this->user->id;
            return $postModel->save(false);
        }
    }
}
