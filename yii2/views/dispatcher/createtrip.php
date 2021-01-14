<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;
use yii\grid\GridView;

use app\models\Trip;
use app\models\Port;
use app\models\Ship;

/* @var $this yii\web\View */
/* @var $model app\models\Request */
/* @var $form yii\widgets\ActiveForm */
?>



<div class="request-form">


	<h1> Создание рейса </h1>
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'PK_PortSend',
            'PK_PortReceive',
            'TimeDuration',
            ['class' => 'yii\grid\ActionColumn',
            'template' => '',
            ]
        ],
    ]); ?>



    <?php $form = ActiveForm::begin(['action' => ['dispatcher/createtrip', 'modelsRoute' => $modelsRoute], 'method' => 'post',]); ?>

    <?= $form->field($model, 'PK_PortReceive')->textInput() ?>

    <?= $form->field($model, 'PK_PortSend')->textInput() ?>

    <?= $form->field($model, 'TimeDuration')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить маршрут', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
