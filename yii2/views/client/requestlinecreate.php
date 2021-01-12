<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;
use yii\grid\GridView;

use app\models\Client;
use app\models\Trip;
use app\models\Port;

?>

<div class="request-form">

	<h1> Состав заявки </h1>
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'CargoName',
            'CargoAmount',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '',
            ]
        ],
    ]); ?>

    <?php $form = ActiveForm::begin(['action' =>['client/addline', 'PK_Request' => $PK_Request], 'method' => 'post',]); ?>

    <?= $form->field($model, 'CargoName')->textInput() ?>

    <?= $form->field($model, 'CargoAmount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить к заявке', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?= Html::a('Готово', ['client/viewrequest', 'id' => $PK_Request], ['class'=>'btn btn-primary']) ?>

</div>
