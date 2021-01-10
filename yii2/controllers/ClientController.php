<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;


class ClientController extends Controller
{
	public function actionSchedule()
	{
		return $this->render('schedule');
	}

		public function actionRequests()
	{
		return $this->render('requests');
	}
}