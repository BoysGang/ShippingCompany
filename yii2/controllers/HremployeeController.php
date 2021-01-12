<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\data\ActiveDataProvider;

use app\models\Hremployee;
use app\models\Dispatcher;
use app\models\Booker;

class HremployeeController extends Controller
{

public function console_log($data)
{
    echo "<script>";
    echo "console.log(" . json_encode($data) . ")";
    echo "</script>";
}
	//action render page Index
	public function actionIndex()
	{
		//получение источников данных для всех сотрудников
		//с предварительным получением запросов
		$query = Hremployee::find();
		$dataProviderHR = new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
		        'pageSize' => 10,
		    	],
		]);

		$query = Dispatcher::find();
		$dataProviderDP = new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
		        'pageSize' => 10,
		    	],
		]);

		$query = Booker::find();
		$dataProviderBK = new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
		        'pageSize' => 10,
		    	],
		]);

		//рендер главной страницы
		return $this->render('index', [
			'dataProviderHR' => $dataProviderHR,
			'dataProviderDP' => $dataProviderDP,
			'dataProviderBK' => $dataProviderBK,
		]
	);

	}

	//Добавить бухгалтера
    public function actionCreatebooker()
    {
        $model = new Booker();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('createbooker', [
            'model' => $model,
        ]);
    }

}
