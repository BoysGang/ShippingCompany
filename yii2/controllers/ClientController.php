<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\data\ActiveDataProvider;

use app\models\Client;
use app\models\Request;
use app\models\RequestSearch;


class ClientController extends Controller
{
	public function actionSchedule()
	{
		return $this->render('schedule');
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