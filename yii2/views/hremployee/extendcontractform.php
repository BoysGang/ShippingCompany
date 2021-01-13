<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;
use \yii\jui\DatePicker;

use app\models\CrewPosition;
use app\models\Ship;
use app\models\CrewMember;

/* @var $this yii\web\View */
/* @var $model app\models\Contract */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contract-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'PK_CrewMember')
	->dropDownList(ArrayHelper::map(CrewMember::find()->where(["PK_CrewMember" => $oldModel->PK_CrewMember])->all(), 'PK_CrewMember', 'FullName')) ?>

    <?= $form->field($model, 'DateConclusion')->textInput(['readonly' => true, 'placeholder' => date("Y-m-d")]) ?>

	<?= $form->field($model, 'DateExpiration')->widget(DatePicker::classname(), ['language' => 'ru', 'dateFormat' => 'yyyy-MM-dd']) ?>

	<?= $form->field($model, 'PK_Ship')->dropDownList(ArrayHelper::map(Ship::find()->all(), 'PK_Ship', 'ShipNumName')) ?>

	<?= $form->field($model, 'PK_CrewPosition')->dropDownList(ArrayHelper::map(CrewPosition::find()->all(), 'PK_CrewPosition', 'CrewPositionName')) ?>

    <?= $form->field($model, 'Salary')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>