<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HRemployee */

$this->title = 'Добавить Кадровика';
?>
<div class="hremployee-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('createhremployeeform', [
        'model' => $model,
    ]) ?>

</div>