<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HREmployee */

$this->title = 'Обновить Кадровика: ' . $model->PK_HREmployee;

?>
<div class="hremployee-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('createhremployeeform', [
        'model' => $model,
    ]) ?>

</div>