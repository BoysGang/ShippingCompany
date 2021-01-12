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

<h1>Заявки пользователя:</h1>

      <p>
        <?= Html::a('Подать заявку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'RequestNum',
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
             'attribute' =>'PK_Receiver',
             'value' => 'ReceiverFullName'
        	],
            'RequestStatus',

            ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'buttons' =>[
                'view' => function ($url, $model)
                {
                    $url = Url::to(['client/viewrequest', 'id' => $model->PK_Request]);
                    return Html::a('Подробности', $url, ['title' => 'view']);
                }],
            ]
        ],
    ]); ?>