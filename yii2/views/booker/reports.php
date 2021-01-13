<?php
	use yii\bootstrap\NavBar;
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\bootstrap\Nav;
	use yii\grid\GridView;

?>
<div class="horizontalMenu">
	<ul class="nav nav-tabs">
	  <li class="nav-item">
		<?= Html::a('Расписание', ['booker/schedule'], ['class' => 'btn-secondary btn-lg']) ?>
	  </li>
	  <li class="nav-item">
		<?= Html::a('Отчеты о доходности', ['booker/reports'], ['class' => 'btn-secondary btn-lg']) ?>
	  </li>
	</ul>
</div>

<h1>Отчеты о доходности рейсов:</h1>
    </script>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'PK_Trip',
            'FirstPort',
            'LastPort',
            'Profit',
            'CostInRubles',
            'NetProfit',
            'UnitPriceRubles',
            'DateDeparture',
            'DateArrival',
            [
             'attribute' => 'PK_Ship',
             'value' => 'ShipName'
            ],

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'buttons' =>[
            	'view' => function ($url, $model)
            	{
            		$url = Url::to(['booker/viewtrip', 'id' => $model->PK_Trip]);
            		return Html::a('Подробнее..', $url, ['title' => 'view']);
            	}
            ]],
        ],
    ]);
    ?>