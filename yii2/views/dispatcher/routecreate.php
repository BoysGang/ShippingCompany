<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;
use yii\grid\GridView;

use app\models\Trip;
use app\models\Port;

?>

<div class="route-form">

	<h1> Содержание рейса </h1>
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
             'attribute' =>'PK_PortSend',
             'value' => function ($model) {
                return $model->pKPortSend->PortName;
             },
            ],
            [
             'attribute' =>'PK_PortReceive',
             'value' =>  function ($model) {
                return $model->pKPortReceive->PortName;
             },
            ],

             'TimeDuration',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' =>[
                'delete' => function ($url, $model)
                    {
                        $url = Url::to(['#']);
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,
                        ['title' => 'delete',
                         'data-confirm' => Yii::t('yii', 'Вы уверены что хотите удалить элемент?'),
                         'data-method' => 'post',]);
                    }
                ],
            ]
        ],
    ]); ?>

    <?php $form = ActiveForm::begin(['action' =>['dispatcher/addroute', 'PK_Trip' => $PK_Trip], 'method' => 'post',]); ?>

    <?= $form->field($model, 'PK_PortSend')->dropDownList(ArrayHelper::map(Port::find()->all(), 'PK_Port', 'PortName')) ?>

    <?= $form->field($model, 'PK_PortReceive')->dropDownList(ArrayHelper::map(Port::find()->all(), 'PK_Port', 'PortName')) ?>

    <?= $form->field($model, 'TimeDuration')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Добавить маршрут', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?= Html::a('Готово', ['dispatcher/addtrip', 'id' => $PK_Trip], ['class'=>'btn btn-primary']) ?>

</div>
