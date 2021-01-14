<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\data\ActiveDataProvider;

use app\models\Request;
use app\models\Dispatcher;
use app\models\Client;
use app\models\Trip;
use app\models\RequestLine;
use app\models\Consignment;
use app\models\Booker;
use app\models\Ship;

class DispatcherController extends Controller
{
	public function console_log($data)
	{
		echo "<script>";
		echo "console.log(" . json_encode($data) . ")";
		echo "</script>";
	}

	public function alert($string)
	{
		echo "<script>";
		echo "alert(" . json_encode($string) . ")";
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

	public function actionConsignments()
	{
		$query = Consignment::find();

		$dataProvider = new ActiveDataProvider([
		    'query' => $query,
	    	'pagination' => [
	        'pageSize' => 20,
	    	],
		]);

		return $this->render('consignments', [
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionViewrequest($id)
	{
		$bookerModel = new Booker();

		$query = RequestLine::find()->where(['PK_Request' => $id]);
        $dataProvider = new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
		        'pageSize' => 20,
	    	],
		]);

        return $this->render('viewrequest', [
			'requestModel' => Request::find()->where(["PK_Request" => $id])->one(),
			'bookerModel' => $bookerModel,
            'dataProvider' => $dataProvider
        ]);
	}

	public function actionCreateconsignment($PK_Request)
	{
		if (Yii::$app->request->post())
		{
			$PK_Dispatcher = Dispatcher::find()
				->select('PK_Dispatcher')
				->where(['PersonnelNum' => Yii::$app->user->identity->getUsername()])->one()->PK_Dispatcher;

			$PK_Booker = Yii::$app->request->post()['Booker']['PK_Booker'];

			//Consignment creation
			$query = 'select createconsignment(' . $PK_Request . ');';
			Yii::$app->db->createCommand($query)->execute();

			$PK_Consignment = Consignment::find()
				->select('PK_Consignment')
				->where(['PK_Request' => $PK_Request])->one()->PK_Consignment;

			//Trying accept a consignment
			$query = 'select checkdispatcher(' 
				. $PK_Dispatcher 
				. ', ' 
				. $PK_Booker 
				. ', ' 
				. $PK_Consignment 
				. ', ' 
				. 'true' 
				. ');';

			if (Yii::$app->db->createCommand($query)->queryOne()['checkdispatcher'])
				$this->alert('Заявка одобрена, коносамент создан!');
			else
				$this->alert('Заявка отклонена, превышена грузоподъемность!');
		}

		return $this->actionViewrequest($PK_Request);
	}

	public function actionDeclinerequest($PK_Request)
	{
		$PK_Dispatcher = Dispatcher::find()
			->select('PK_Dispatcher')
			->where(['PersonnelNum' => Yii::$app->user->identity->getUsername()])->one()->PK_Dispatcher;


		//Consignment creation
		$query = 'select createconsignment(' . $PK_Request . ');';
		Yii::$app->db->createCommand($query)->execute();

		$PK_Consignment = Consignment::find()
			->select('PK_Consignment')
			->where(['PK_Request' => $PK_Request])->one()->PK_Consignment;

		//Decline a consignment
		$query = 'select checkdispatcher(' 
			. $PK_Dispatcher 
			. ', ' 
			. '2'
			. ', ' 
			. $PK_Consignment 
			. ', ' 
			. 'false' 
			. ');';

		Yii::$app->db->createCommand($query)->execute();

		$this->alert('Заявка отклонена, вы так сказали!');

		return $this->actionViewrequest($PK_Request);
	}

	public function actionShips() {
		$query = Ship::find();

		$dataProvider = new ActiveDataProvider([
		    'query' => $query,
	    	'pagination' => [
	        'pageSize' => 20,
	    	],
		]);

		return $this->render('ships', [
			'dataProvider' => $dataProvider,
		]);
	}

	/*

	Работа с кораблем: добавление, удаление, изменение

	*/
	// Добавить корабль
    public function actionCreateship()
    {
        $model = new Ship();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('ships');
        }

        return $this->render('createship', [
            'model' => $model,
        ]);
    }

    // Удалить корабль
    public function actionDeleteship($id)
    {
        $query = 'delete from "Ship" where "PK_Ship" = '. $id .';';
        $data = Yii::$app->db->createCommand($query)->queryOne();

        return $this->redirect(['ships']);
    }

    // Изменить корабль
    public function actionUpdateship($id)
    {
        $model = Ship::find()->where(['PK_Ship' => $id])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('ships');
        }

        return $this->render('updateship', [
            'model' => $model,
        ]);
    }
}
