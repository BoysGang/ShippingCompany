<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\data\ActiveDataProvider;

use app\models\Client;
use app\models\Trip;
use app\models\Port;
use app\models\Request;
use app\models\RequestLine;
use app\models\Route;


class ClientController extends Controller
{

public function console_log($data)
{
    echo "<script>";
    echo "console.log(" . json_encode($data) . ")";
    echo "</script>";
}
	public function actionSchedule()
	{
		$query = Trip::find()->select('Trip.*, Route.*')
			->leftJoin('Route', '"Route"."PK_Trip" = "Trip"."PK_Trip"')
			->where(['>', 'DateDeparture', date("Y-m-d H:i:s")]);
		$dataProvider = new ActiveDataProvider([
		    'query' => $query,
	    	'pagination' => [
	        'pageSize' => 20,
	    	],
		]);

		return $this->render('schedule', [
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionViewtrip($id)
	{
		$query = Route::find()->where(['PK_Trip' => $id]);
        $dataProvider = new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
		        'pageSize' => 20,
	    	],
		]);

        return $this->render('viewtrip', [
            'model' => Trip::find()->where(["PK_Trip" => $id])->one(),
            'dataProvider' => $dataProvider
        ]);
	}

	public function actionRequests()
	{
		$PK_ClientSender = Client::find()->Select('PK_Client')->where(['FullName' => Yii::$app->user->identity->getUsername()])->one();
		$query = Request::find()->select('Request.*')->where(['PK_Sender' => $PK_ClientSender]);
        $dataProvider = new ActiveDataProvider([
	    'query' => $query,
	    'pagination' => [
	        'pageSize' => 20,
	    	],
		]);

        return $this->render('requests', [
            'dataProvider' => $dataProvider,
        ]);
	}


	public function actionViewrequest($id)
	{
		$query = RequestLine::find()->where(['PK_Request' => $id]);
        $dataProvider = new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
		        'pageSize' => 20,
	    	],
		]);
        return $this->render('viewrequest', [
            'model' => Request::find()->where(["PK_Request" => $id])->one(),
            'dataProvider' => $dataProvider
        ]);
	}

    public function actionCreate()
    {
        $model = new Request();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['viewrequest', 'id' => $model->PK_Request]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    //Получение порта отправления по номеру рейса
    public function getportstart($id)
    {
    	$port = Port::find()->leftJoin()->where("PK_Trip" => $id)->one();
    }
}