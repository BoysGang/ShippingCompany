<?php

use yii\helpers\Html;

$this->title = 'Добавить тип корабля';
?>
<div class="ship-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('createshiptypeform', [
        'model' => $model,
    ]) ?>

</div>