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
