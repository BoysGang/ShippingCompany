<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RequestSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'PK_Request') ?>

    <?= $form->field($model, 'RequestNum') ?>

    <?= $form->field($model, 'PK_Sender') ?>

    <?= $form->field($model, 'PK_Receiver') ?>

    <?= $form->field($model, 'PK_Trip') ?>

    <?php // echo $form->field($model, 'PK_PortReceive') ?>

    <?php // echo $form->field($model, 'PK_PortSend') ?>

    <?php // echo $form->field($model, 'RequestStatus') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
