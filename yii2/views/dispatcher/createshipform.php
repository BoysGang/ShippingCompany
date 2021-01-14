<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use app\models\Ship;
use app\models\ShipType;

?>

<div class="ship-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ShipNumber')->textInput() ?>

    <?= $form->field($model, 'ShipName')->textInput() ?>

    <?= $form->field($model, 'PK_ShipType')->dropDownList(ArrayHelper::map(ShipType::find()->all(), 'PK_ShipType', 'ShipTypeName')) ?>


    <div class="form-group">
        <?= Html::submitButton('Ок', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>