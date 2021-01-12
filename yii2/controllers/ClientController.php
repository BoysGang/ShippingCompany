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
        if (Yii::$app->request->post()) {

        	$requestNumber = uniqid("q", true);
        	$userPK = (Client::find()->Select('PK_Client')->where(['FullName' => Yii::$app->user->identity->getUsername()])->one()->PK_Client);
        	$_POST = Yii::$app->request->post()['Request'];

	        $query = 'select createrequest(\''. $requestNumber . '\', ' .
	        	$userPK . ', ' .
	        	$_POST['PK_Receiver'] . ', ' .
	        	$_POST['PK_Trip'] . ', ' .
	        	$_POST['PK_PortReceive'] . ', ' .
	        	$_POST['PK_PortSend'] . ');';
	        //выполнение запроса
        	Yii::$app->db->createCommand($query)->execute();

        	//получение PK request
        	$model = Request::find()->where(['RequestNum' => $requestNumber])->one();

            return $this->actionAddline($model->PK_Request);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionAddline($PK_Request)
    {
    	$model = new RequestLine();

    	if($model->load(Yii::$app->request->post()))
    	{
        	$_POST = Yii::$app->request->post()['RequestLine'];

	        $query = 'select createrequestline(\'' .
	        	$_POST['CargoName'] . '\', ' .
	        	$_POST['CargoAmount'] . ', ' .
	        	$PK_Request . ');';
	        //выполнение запроса
        	Yii::$app->db->createCommand($query)->execute();
    	}

		$query = RequestLine::find()->where(['PK_Request' => $PK_Request]);
        $dataProvider = new ActiveDataProvider([
	    'query' => $query,
	    'pagination' => [
	        'pageSize' => 20,
	    	],
		]);

    	return $this->render('requestlinecreate', [
    		'PK_Request' => $PK_Request,
    		'model' => $model,
    		'dataProvider' => $dataProvider,
    	]);
    }

}