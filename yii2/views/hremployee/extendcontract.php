<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Contract */

$this->title = 'Продлить контракт';
?>
<div class="contract-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('extendcontractform', [
        'model' => $model,
        'oldModel' => $oldModel
    ]) ?>

</div>