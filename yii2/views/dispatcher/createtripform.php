<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;
use \yii\jui\DatePicker;

use app\models\Trip;
use app\models\Ship;

/* @var $this yii\web\View */
/* @var $model app\models\Trip */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="trip-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Cost')->textInput() ?>
    <?= $form->field($model, 'UnitPrice')->textInput() ?>

    <?= $form->field($model, 'DateDeparture')->widget(DatePicker::classname(), ['language' => 'ru', 'dateFormat' => 'yyyy-MM-dd']) ?>

    <?= $form->field($model, 'PK_Ship')->dropDownList(ArrayHelper::map(Ship::find()->all(), 'PK_Ship', 'ShipNumName')) ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
