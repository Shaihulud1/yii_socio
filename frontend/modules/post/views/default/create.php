<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="post-create">
    
    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'picture')->fileInput() ?>
        <?= $form->field($model, 'description') ?>

        <div class="form-group">
            <?= Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>
