<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;

use app\models\Booker;

?>
    <h1> Информация о заявке </h1>
    <?= DetailView::widget([
        'model' => $requestModel,
        'attributes' => [
            'RequestNum',
            'PK_Trip',
            'PortSendName',
            'PortReceiveName',
            'ReceiverFullName',
            'RequestStatus',
        ],
    ]) ?>
    <h2> Состав заявки: </h2>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'CargoName',
            'CargoAmount',

            ['class' => 'yii\grid\ActionColumn', 'template' => ''],
        ],
    ]); ?>
