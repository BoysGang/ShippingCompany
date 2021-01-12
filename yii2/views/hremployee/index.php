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
		<?= Html::a('Все сотрудники', ['hremployee/index'], ['class' => 'btn-secondary btn-lg']) ?>
	  </li>
	  <li class="nav-item">
		<?= Html::a('Контракты', ['hremployee/contracts'], ['class' => 'btn-secondary btn-lg']) ?>
	  </li>
  	  <li class="nav-item">
		<?= Html::a('Истекающие контракты', ['client/expiringcontracts'], ['class' => 'btn-secondary btn-lg']) ?>
	  </li>
	</ul>
</div>

<h1> Сотрудники компании </h1>
		<hr>

<hr>
<h2> Диспетчеры: </h2>
<?= GridView::widget([
        'dataProvider' => $dataProviderDP,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'FullName',
            'PersonnelNum',
            'SalaryRubles',
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            ]
        ],
    ]); ?>

<hr>
<h2> Бухгалтеры: </h2>
<p>
	<?= Html::a('Добавить бухгалтера', ['createbooker'], ['class' => 'btn btn-success']) ?>
</p>
<?= GridView::widget([
        'dataProvider' => $dataProviderBK,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'FullName',
            'PersonnelNum',
            'SalaryRubles',
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            ]
        ],
    ]); ?>

<hr>
<h2> Кадровики:  </h2>
<?= GridView::widget([
        'dataProvider' => $dataProviderHR,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'FullName',
            'PersonnelNum',
            'SalaryRubles',
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            ]
        ],
    ]); ?>