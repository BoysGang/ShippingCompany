<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Booker */

$this->title = 'Обновить бухгалтера: ' . $model->PK_Booker;

?>
<div class="booker-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('createbookerform', [
        'model' => $model,
    ]) ?>

</div>