<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Booker */

$this->title = 'Добавить Бухгалтера';
?>
<div class="booker-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('createbookerform', [
        'model' => $model,
    ]) ?>

</div>