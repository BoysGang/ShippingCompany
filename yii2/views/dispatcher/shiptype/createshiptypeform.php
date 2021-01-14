<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use app\models\Ship;
use app\models\ShipType;

?>

<div class="ship-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ShipTypeName')->textInput() ?>
    
    <?= $form->field($model, 'CarryingCapacity')->textInput() ?>

    <?= $form->field($model, 'AmountCaptains')->textInput() ?>

    <?= $form->field($model, 'AmountCaptainHelpers')->textInput() ?>

    <?= $form->field($model, 'AmountCooks')->textInput() ?>

    <?= $form->field($model, 'AmountMechanics')->textInput() ?>

    <?= $form->field($model, 'AmountElectricians')->textInput() ?>

    <?= $form->field($model, 'AmountSailors')->textInput() ?>

    <?= $form->field($model, 'AmountRadioOperators')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Ок', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>