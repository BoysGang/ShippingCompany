<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Booker */

?>
<div class="crewmember-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('createcrewmemberform', [
        'model' => $model,
    ]) ?>

</div>