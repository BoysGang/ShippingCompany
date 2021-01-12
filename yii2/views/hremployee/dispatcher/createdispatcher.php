<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Dispatcher */

$this->title = 'Добавить Диспетчера';
?>
<div class="dispatcher-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('createdispatcherform', [
        'model' => $model,
    ]) ?>

</div>