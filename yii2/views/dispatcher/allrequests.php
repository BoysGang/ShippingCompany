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
	</ul>
</div>

<h1>Все заявки пользователей:</h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'PK_Trip',
            [
                'attribute' =>'PK_PortSend',
                'value' => 'PortSendName'
        	],
        	[
                'attribute' => 'PK_PortReceive',
                'value' => 'PortReceiveName'
            ],
            [
                'attribute' => 'PK_Sender',
                'value' => 'SenderFullName'
            ],
            [
                'attribute' =>'PK_Receiver',
                'value' => 'ReceiverFullName'
          ],
          'RequestStatus',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'buttons' =>[
                'view' => function ($url, $model)
                {
                    $url = Url::to(['dispatcher/viewallrequest', 'id' => $model->PK_Request]);
                    return Html::a('Подробности', $url, ['title' => 'view']);
                }],
            ]
        ],
    ]); ?>