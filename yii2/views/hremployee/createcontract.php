<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Dispatcher */

$this->title = 'Заключить контракт';
?>
<div class="contract-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('createcontractform', [
        'model' => $model,
    ]) ?>

</div>