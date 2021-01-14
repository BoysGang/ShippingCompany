<?php

use yii\helpers\Html;

$this->title = 'Изменить тип: ';

?>
<div class="ship-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('createshiptypeform', [
        'model' => $model,
    ]) ?>

</div>