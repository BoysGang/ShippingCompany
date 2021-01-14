<?php
	use yii\helpers\Html;
    use yii\helpers\Url;
	use yii\grid\GridView;
    use yii\widgets\Pjax;
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

<h1> Сотрудники компании </h1>
		<hr>

<hr>
<h2> Диспетчеры: </h2>
<p>
	<?= Html::a('Добавить диспетчера', ['createdispatcher'], ['class' => 'btn btn-success']) ?>
</p>
<?php Pjax::begin(); ?>
<?= GridView::widget([
        'dataProvider' => $dataProviderDP,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'FullName',
            'PersonnelNum',
            'SalaryRubles',
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'buttons' =>[
            	'delete' => function ($url, $model)
            	{
            		$url = Url::to(['hremployee/deletedispatcher', 'id' => $model->PK_Dispatcher]);
            		return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,
            		['title' => 'delete',
            		 'data-confirm' => Yii::t('yii', 'Вы уверены что хотите удалить элемент?'),
            		 'data-method' => 'post',]);
            	},
            	'update' => function ($url, $model)
            	{
            		$url = Url::to(['hremployee/updatedispatcher', 'id' => $model->PK_Dispatcher]);
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>',  $url);
            	}
        		],
            ]
        ],
    ]); ?>
<?php Pjax::end(); ?>
<hr>
<h2> Бухгалтеры: </h2>
<p>
	<?= Html::a('Добавить бухгалтера', ['createbooker'], ['class' => 'btn btn-success']) ?>
</p>
<?php Pjax::begin(); ?>
<?= GridView::widget([
        'dataProvider' => $dataProviderBK,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'FullName',
            'PersonnelNum',
            'SalaryRubles',
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'buttons' =>[
            	'delete' => function ($url, $model)
            	{
            		$url = Url::to(['hremployee/deletebooker', 'id' => $model->PK_Booker]);
            		return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,
            		['title' => 'delete',
            		 'data-confirm' => Yii::t('yii', 'Вы уверены что хотите удалить элемент?'),
            		 'data-method' => 'post',]);
            	},
            	'update' => function ($url, $model)
            	{
            		$url = Url::to(['hremployee/updatebooker', 'id' => $model->PK_Booker]);
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>',  $url);
            	}
            	],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>

<hr>
<h2> Кадровики:  </h2>
<p>
	<?= Html::a('Добавить кадровика', ['createhremployee'], ['class' => 'btn btn-success']) ?>
</p>
<?php Pjax::begin(); ?>
<?= GridView::widget([
        'dataProvider' => $dataProviderHR,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'FullName',
            'PersonnelNum',
            'SalaryRubles',
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'buttons' =>[
            	'delete' => function ($url, $model)
            	{
            		$url = Url::to(['hremployee/deletehremployee', 'id' => $model->PK_HREmployee]);
            		return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,
            		['title' => 'delete',
            		 'data-confirm' => Yii::t('yii', 'Вы уверены что хотите удалить элемент?'),
            		 'data-method' => 'post',]);
            	},
            	'update' => function ($url, $model)
            	{
            		$url = Url::to(['hremployee/updatehremployee', 'id' => $model->PK_HREmployee]);
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>',  $url);
            	}
            	],
            ]
        ],
    ]); ?>

<?php Pjax::end(); ?>

<hr>
<h2> Члены экипажа судов:  </h2>
<p>
    <?= Html::a('Добавить члена экипажа', ['createcrewmember'], ['class' => 'btn btn-success']) ?>
</p>
<?php Pjax::begin(); ?>
<?= GridView::widget([
        'dataProvider' => $dataProviderCM,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'FullName',
            ['class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'buttons' =>[
                'delete' => function ($url, $model)
                {
                    $url = Url::to(['hremployee/deletecrewmember', 'id' => $model->PK_CrewMember]);
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url,
                    ['title' => 'delete',
                     'data-confirm' => Yii::t('yii', 'Вы уверены что хотите удалить элемент?'),
                     'data-method' => 'post',]);
                },
                'update' => function ($url, $model)
                {
                    $url = Url::to(['hremployee/updatecrewmember', 'id' => $model->PK_CrewMember]);
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>',  $url);
                }
                ],
            ]
        ],
    ]); ?>
<?php Pjax::end(); ?>
