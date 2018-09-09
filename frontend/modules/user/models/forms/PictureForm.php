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
            [['picture'], 'file', 'extensions' => 'jpg', 'checkExtensionByMimeType' => true],
        ];
    }

    public function save($pictureFile)
    {
        $this->picture = $pictureFile;
        print_R($pictureFile);
    }

    public function savePrepareResult($pictureFile)
    {
        $this->picture = $pictureFile;
        if($this->validate()){
            $user = Yii::$app->user->identity;
            $fileName = strtolower(md5(uniqid($user->id.$user->username))).'.'.$this->picture->extension;
            $user->prepareImage = $fileName;
            $user->save(false, ['prepareImage']);
            $this->picture->saveAs(Yii::getAlias('@web').'upload/images/profile/'.$fileName);
            return true;
        }else{
            print_r($this->getErrors());
            return false;
        }
    }
}
