<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\data\ActiveDataProvider;

use app\models\Request;
use app\models\Client;
use app\models\Trip;

class DispatcherController extends Controller
{
	public function console_log($data)
	{
		echo "<script>";
		echo "console.log(" . json_encode($data) . ")";
		echo "</script>";
	}

	public function actionRequests()
	{
		$query = Request::find()->where(['RequestStatus' => 'verifying']);

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
}
