<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;

use app\models\Client;
use app\models\Trip;

/* @var $this yii\web\View */
/* @var $model app\models\Request */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'PK_Receiver')->dropDownList(ArrayHelper::map(Client::find()->all(), 'PK_Client', 'FullName')) ?>
    <?= $form->field($model, 'PK_Trip')->dropDownList(ArrayHelper::map(Trip::getActiveTrips(), 'PK_Trip', 'PK_Trip')) ?>

    <?= $form->field($model, 'PK_PortSend')->dropDownList($arrState, [
        'prompt' => 'Выбрать...',
        'onchange' => '
            $.post("/client/getportstart?id='. '"+$(this).val(), function (data){
                            $("select#request-pk_portsend").html(data);
                });',
    ]) ?>

    <?= $form->field($model, 'PK_PortReceive')->textInput() ?>

    <?= $form->field($model, 'RequestStatus')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
