<?php
	use yii\bootstrap\NavBar;
	use yii\helpers\Html;
	use yii\bootstrap\Nav;
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