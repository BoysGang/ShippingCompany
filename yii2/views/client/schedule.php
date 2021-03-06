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
		<?= Html::a('Расписание', ['client/schedule'], ['class' => 'btn-secondary btn-lg']) ?>
	  </li>
	  <li class="nav-item">
		<?= Html::a('Заявки', ['client/requests'], ['class' => 'btn-secondary btn-lg']) ?>
	  </li>
	</ul>
</div>

<h1>Расписание рейсов Компании:</h1>
    </script>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'PK_Trip',
            'FirstPort',
            'LastPort',
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
            		$url = Url::to(['client/viewtrip', 'id' => $model->PK_Trip]);
            		return Html::a('Подробнее..', $url, ['title' => 'view']);
            	}
            ]],
        ],
    ]);
    ?>