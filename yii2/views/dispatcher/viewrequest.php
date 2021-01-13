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
    <h2> Состав рейса: </h2>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'CargoName',
            'CargoAmount',

            ['class' => 'yii\grid\ActionColumn', 'template' => ''],
        ],
    ]); ?>

<div class="request-form">

    <?php $form = ActiveForm::begin(['action' =>['dispatcher/createconsignment', 'PK_Request' => $requestModel->PK_Request], 'method' => 'post',]); ?>

    <?= $form->field($bookerModel, 'PK_Booker')->dropDownList(ArrayHelper::map(Booker::find()->all(), 'PK_Booker', 'FullName')) ?>

    <div class="form-group">
        <?= Html::submitButton('Принять заявку', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?= Html::a('Отклонить заявку', ['dispatcher/declinerequest', 'PK_Request' => $requestModel->PK_Request], ['class'=>'btn btn-danger']) ?>

</div>
