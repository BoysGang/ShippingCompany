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

<h1>Список морских судов:</h1>
<p>
	<?= Html::a('Добавить', ['createship'], ['class' => 'btn btn-success']) ?>
</p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ShipNumber',
            'ShipName',
            [
              'attribute' => 'PK_ShipType',
              'value' => function($model){
                return $model->pKShipType->ShipTypeName;
              }
             ],
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'buttons' =>[
            	'delete' => function ($url, $model)
            	{
            		$url = Url::to(['dispatcher/deleteship', 'id' => $model->PK_Ship]);
            		return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,
            		['title' => 'delete',
            		 'data-confirm' => Yii::t('yii', 'Вы уверены что хотите удалить элемент?'),
            		 'data-method' => 'post',]);
            	},
            	'update' => function ($url, $model)
            	{
            		$url = Url::to(['dispatcher/updateship', 'id' => $model->PK_Ship]);
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>',  $url);
            	}
        		]],
        ],
    ]);
    ?>