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
		<?= Html::a('Все заявки', ['dispatcher/allrequests'], ['class' => 'btn-secondary btn-lg']) ?>
	  </li>
      <li class="nav-item">
		<?= Html::a('Новые заявки', ['dispatcher/requests'], ['class' => 'btn-secondary btn-lg']) ?>
	  </li>
	  <li class="nav-item">
		<?= Html::a('Расписание', ['dispatcher/schedule'], ['class' => 'btn-secondary btn-lg']) ?>
	  </li>
      <li class="nav-item">
		<?= Html::a('Коносаменты', ['dispatcher/consignments'], ['class' => 'btn-secondary btn-lg']) ?>
	  </li>
      <li class="nav-item">
		<?= Html::a('Морские суда', ['dispatcher/ships'], ['class' => 'btn-secondary btn-lg']) ?>
	  </li>
	  <li class="nav-item">
		<?= Html::a('Типы судов', ['dispatcher/shiptypes'], ['class' => 'btn-secondary btn-lg']) ?>
	  </li>
	</ul>
</div>

<h1>Коносаменты:</h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'PK_Consignment',
            'BookingDateFormatted',
            'TotalPriceInRubles',
            [
                'attribute' => 'PK_Request',
                'value' => function($model)
                {
                    return $model->pKRequest->RequestNum;
                }
            ],
            [
                'attribute' => 'PK_Booker',
                'value' => function($model)
                {
                    if ($model->pKBooker)
                        return $model->pKBooker->FullName;
                    else
                        return '-';
                }
            ],
            [
                'attribute' => 'PK_Dispatcher',
                'value' => function($model)
                {
                    if ($model->pKDispatcher)
                        return $model->pKDispatcher->FullName;
                    else
                        return '-';
                }
            ],

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'buttons' =>[
                'view' => function ($url, $model)
                {
                    $url = Url::to(['dispatcher/viewrequest', 'id' => $model->PK_Request]);
                    return Html::a('Подробности', $url, ['title' => 'view']);
                }],
            ]
        ],
    ]); ?>