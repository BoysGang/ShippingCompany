<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;

use app\models\Client;
use app\models\Trip;
use app\models\Port;

/* @var $this yii\web\View */
/* @var $model app\models\Request */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'PK_Receiver')->dropDownList(ArrayHelper::map(Client::find()->all(), 'PK_Client', 'FullName')) ?>
    <?= $form->field($model, 'PK_Trip')->dropDownList(ArrayHelper::map(Trip::getActiveTrips(), 'PK_Trip', 'PK_Trip')) ?>

    <?= $form->field($model, 'PK_PortSend')->dropDownList(ArrayHelper::map(Port::find()->all(), 'PK_Port', 'PortName')) ?>

    <?= $form->field($model, 'PK_PortReceive')->dropDownList(ArrayHelper::map(Port::find()->all(), 'PK_Port', 'PortName')) ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
