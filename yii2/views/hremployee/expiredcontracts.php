<?php
	use yii\helpers\Html;
    use yii\helpers\Url;
	use yii\grid\GridView;
?>
<div class="horizontalMenu">
	<ul class="nav nav-tabs">
	  <li class="nav-item">
		<?= Html::a('Все сотрудники', ['hremployee/index'], ['class' => 'btn-secondary btn-lg']) ?>
	  </li>
	  <li class="nav-item">
		<?= Html::a('Контракты', ['hremployee/contracts'], ['class' => 'btn-secondary btn-lg']) ?>
	  </li>
  	  <li class="nav-item">
		<?= Html::a('Истекающие контракты', ['hremployee/expiredcontracts'], ['class' => 'btn-secondary btn-lg']) ?>
	  </li>
	</ul>
</div>


<h1> Истекающие контракты </h1>
<p>
    <?= Html::a('Заключить новый контракт', ['createcontract'], ['class' => 'btn btn-success']) ?>
</p>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
             'attribute' => 'PK_CrewMember',
             'value' => function ($model) {
             			return $model->pKCrewMember->FullName;}
        	],
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
            [
             'attribute' => 'PK_HREmployee',
             'value' => function($model) {
             			return $model->pKHREmployee->FullName . ' (' . $model->pKHREmployee->PersonnelNum . ')';}
        	],
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{extend}',
            'buttons' =>[
            		'extend' => function ($url, $model)
	            	{
	            		$url = Url::to(['hremployee/extendcontract', 'id' => $model->PK_Contract]);
	            		return Html::a('Продлить', $url, ['title' => 'extend', 'data-method' => 'post']);
	            	},
                ],
            ]
        ],
    ]); ?>