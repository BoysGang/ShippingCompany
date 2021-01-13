<?php

use yii\helpers\Html;

$this->title = 'Добавить Корабль';
?>
<div class="ship-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('createshipform', [
        'model' => $model,
    ]) ?>

</div>