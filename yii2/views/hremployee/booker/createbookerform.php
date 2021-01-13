<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Booker */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="booker-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'FullName')->textInput() ?>

    <?= $form->field($model, 'PersonnelNum')->textInput() ?>

    <?= $form->field($model, 'Salary')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>