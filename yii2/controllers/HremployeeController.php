<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\data\ActiveDataProvider;

class HremployeeController extends Controller
{
	//action render page Index
	public function actionIndex()
	{
		return $this->render('index');
	}
}
