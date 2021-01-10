<?php
	use yii\bootstrap\NavBar;
	use yii\helpers\Html;
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

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'RequestNum',
            'PK_Trip',
            'PK_PortSend',
            'PK_PortReceive',
            [
             'attribute' =>'PK_Receiver',
             'value' => 'ReceiverFullName'
        	],
            'RequestStatus',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>