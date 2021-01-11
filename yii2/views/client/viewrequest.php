<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

?>
    <h1> Информация о заявке </h1>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'RequestNum',
            'PK_Trip',
            'PortSendName',
            'PortReceiveName',
            'ReceiverFullName',
            'RequestStatus',
        ],
    ]) ?>
    <h2> Состав рейса: </h2>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'CargoName',
            'CargoAmount',

            ['class' => 'yii\grid\ActionColumn', 'template' => ''],
        ],
    ]); ?>