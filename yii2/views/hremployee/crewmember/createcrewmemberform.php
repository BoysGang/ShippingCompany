<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CrewMember */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="crewmember-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'FullName')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>