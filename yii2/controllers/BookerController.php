<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\data\ActiveDataProvider;

use app\models\Trip;
use app\models\Route;

class BookerController extends Controller
{
	public function actionSchedule()
	{	
		$query = Trip::find()
			->select('Trip.*, Route.*')
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

	public function actionReports() {
		$date = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . "-30 days"));

		$query = Trip::find()
			->select('Trip.*, Route.*')
			->leftJoin('Route', '"Route"."PK_Trip" = "Trip"."PK_Trip"')
			->where(['>=', 'DateDeparture', $date])
			->andWhere(['<=', 'DateDeparture', date("Y-m-d H:i:s")]);
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
			'pageSize' => 20,
			],
		]);

		return $this->render('reports', [
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionViewreporttrip($id)
	{
		$query = Route::find()->where(['PK_Trip' => $id]);
		
		$dataProvider = new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
		        'pageSize' => 20,
	    	],
		]);

        return $this->render('viewreporttrip', [
            'model' => Trip::find()->where(["PK_Trip" => $id])->one(),
            'dataProvider' => $dataProvider
        ]);
	}
}
