<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\data\ActiveDataProvider;

use app\models\Client;
use app\models\Trip;
use app\models\Request;
use app\models\RequestSearch;


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
}