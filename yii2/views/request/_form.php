<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use app\models\Client;

/* @var $this yii\web\View */
/* @var $model app\models\Request */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'PK_Receiver')->dropDownList(\yii\helpers\ArrayHelper::map(Client::find()->all(), 'PK_Client', 'FullName')) ?>

    <?= $form->field($model, 'PK_Trip')->textInput() ?>

    <?= $form->field($model, 'PK_PortReceive')->textInput() ?>

    <?= $form->field($model, 'PK_PortSend')->textInput() ?>

    <?= $form->field($model, 'RequestStatus')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>