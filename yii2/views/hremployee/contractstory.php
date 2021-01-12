<?php
	use yii\helpers\Html;
    use yii\helpers\Url;
	use yii\grid\GridView;
?>

<h1> История контрактов: <?= $fullName ?></h1>
<hr>


<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'showOnEmpty' => '-',
        'columns' => [
        	['attribute' => 'PK_CrewPosition',
             'value' => function($model) {
             			return $model->pKCrewPosition->CrewPositionName;}
        	],
            ['attribute' => 'PK_Ship',
             'value' => function($model) {
             			return $model->pKShip->ShipNumName;}
     		],
            'DateConclusion',
            'DateExpiration',
            'SalaryRubles',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '',
            ]
        ],
    ]); ?>