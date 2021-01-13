<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CrewMember */

$this->title = 'Добавить Члена экипажа';
?>
<div class="crewmember-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('createcrewmemberform', [
        'model' => $model,
    ]) ?>

</div>