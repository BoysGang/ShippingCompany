<?php

use yii\helpers\Html;

$this->title = 'Обновить корабль: ';

?>
<div class="ship-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('createshipform', [
        'model' => $model,
    ]) ?>

</div>