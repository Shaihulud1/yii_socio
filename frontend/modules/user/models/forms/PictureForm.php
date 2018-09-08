<?php

namespace frontend\modules\user\models\forms;

use Yii;
use yii\base\Model;

class PictureForm extends Model
{

    public $picture;

    public function rules()
    {
        [['image'], 'required'],
        [['image'], 'file', 'extensions' => 'jpg', 'png'],
    }

    public function save($pictureFile)
    {
        $this->picture = $pictureFile;
        print_R($pictureFile);
    }
}
