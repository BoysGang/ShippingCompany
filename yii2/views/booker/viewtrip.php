<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

?>
<h1> Информация о рейсе </h1>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'PK_Trip',
        'CostInRubles',
        'UnitPriceRubles',
        'DateDeparture',
        'DateArrival',
        'ShipName',
    ],
]) 
?>

<h2> Состав рейса: </h2>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            'TimeDuration',

            [
             'attribute' =>'PK_PortSend',
             'value' => 'PortSendName'
            ],
            [
            'attribute' => 'PK_PortReceive',
            'value' => 'PortReceiveName'
            ],

            ['class' => 'yii\grid\ActionColumn', 'template' => ''],
        ],
    ]); 
?>