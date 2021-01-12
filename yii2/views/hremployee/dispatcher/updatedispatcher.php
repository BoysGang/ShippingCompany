<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Booker */

$this->title = 'Обновить диспетчера: ' . $model->PK_Dispatcher;

?>
<div class="dispatcher-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('createdispatcherform', [
        'model' => $model,
    ]) ?>

</div>