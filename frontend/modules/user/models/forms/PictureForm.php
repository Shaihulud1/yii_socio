<?php

namespace frontend\modules\user\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\User;

class PictureForm extends Model
{

    public $picture;

    public function rules()
    {
        return [
            [['picture'], 'required'],
            [['picture'], 'file', 'extensions' => 'jpg', 'checkExtensionByMimeType' => true, 'message' => 'Wrong extension',],
        ];
    }

    public function save($pictureFile)
    {

    }

    public function savePrepareResult($pictureFile)
    {
        $this->picture = $pictureFile;
        if($this->validate()){
            $user = Yii::$app->user->identity;
            $fileName = strtolower(md5(uniqid($user->id.$user->username))).'.'.$this->picture->extension;
            $oldPicture = $user->prepareImage;
            $user->prepareImage = $fileName;
            $picUrl = Yii::getAlias('@profilesPictureFolder').'/'.$fileName;
            if($user->save(false, ['prepareImage']) && $this->picture->saveAs(Yii::getAlias('@web').'upload/images/profile/'.$fileName)){
                // if(file_exists(Yii::getAlias('@web').'upload/images/profile/'.$oldPicture))
                //     unlink(Yii::getAlias('@web').'upload/images/profile/'.$oldPicture);
                return ['success' => true, 'result' => $picUrl];
            }
        }
        return ['success' => false, 'result' => $this->getErrors()];
    }

    public function setAvatar()
    {
        $user = Yii::$app->user->identity;
        // if(file_exists(Yii::getAlias('@web').'upload/images/profile/'.$user->picture))
        //     unlink(Yii::getAlias('@web').'upload/images/profile/'.$user->picture);
        $user->picture = $user->prepareImage;
        $user->save(false, ['picture']);
    }
}
