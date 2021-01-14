<?php

namespace app\controllers;

use Yii;

use yii\db\Query;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

use app\models\Request;
use app\models\Dispatcher;
use app\models\Client;
use app\models\Trip;
use app\models\RequestLine;
use app\models\Consignment;
use app\models\Booker;
use app\models\Ship;
use app\models\Route;
use app\models\ShipType;

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

	public function actionAllrequests()
	{
		$query = Request::find();

		$dataProvider = new ActiveDataProvider([
		    'query' => $query,
	    	'pagination' => [
	        'pageSize' => 20,
	    	],
		]);

		return $this->render('allrequests', [
			'dataProvider' => $dataProvider,
		]);
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



    public function actionCreatetrip()
    {
    	$model = new Trip();
        if ($model->load(Yii::$app->request->post())) {
        	$model->DateArrival = $model->DateDeparture;
        	$model->save();

        	//получение PK Trip
            return $this->actionAddroute($model->PK_Trip);
        }

        return $this->render('createtrip', [
            'model' => $model,
        ]);
    }

    public function actionAddroute($PK_Trip)
    {
    	$model = new Route();

    	if($model->load(Yii::$app->request->post()))
    	{
        	$_POST = Yii::$app->request->post()['Route'];

        	$query = 'insert into "Route" ("TimeDuration", "IsFirst", "IsLast", "State", "PK_Trip", "PK_PortSend", "PK_PortReceive") values
	        	(\''. $_POST['TimeDuration'] . '\', ' .
	        	'false, ' .
				'false, ' .
				'false, ' .
				$PK_Trip. ', ' .
	        	$_POST['PK_PortSend']  . ', ' .
	        	$_POST['PK_PortReceive'] . ');';
	        //выполнение запроса
        	Yii::$app->db->createCommand($query)->execute();
    	}

		$query = Route::find()->where(['PK_Trip' => $PK_Trip]);
        $dataProvider = new ActiveDataProvider([
	    'query' => $query,
	    'pagination' => [
	        'pageSize' => 20,
	    	],
		]);

    	return $this->render('routecreate', [
    		'PK_Trip' => $PK_Trip,
    		'model' => $model,
    		'dataProvider' => $dataProvider,
    	]);
    }

    public function actionAddtrip($id)
    {
    	$dateArrival = date("Y-m-d H:i:s", strtotime(Trip::find()->where(["PK_Trip" => $id])->one()->DateDeparture));

    	$query = new Query();
    	$data = $query->select('*')->from('"Route"')->where(['"PK_Trip"' => $id])->all();

    	/*расчет даты прибытия*/
    	foreach ($data as $var) {
    		$strMass = explode(' ', $var['TimeDuration']);
    		$dateArrival = date("Y-m-d H:i:s", strtotime($dateArrival . "+" . $strMass[0]  . $strMass[1]));
    	}

    	$query = 'update "Trip" set "DateArrival" = \'' . $dateArrival . '\' where "PK_Trip" = ' . $id;
    	Yii::$app->db->createCommand($query)->execute();


    	$query = 'update "Route" set "IsFirst" = true where "PK_Route" = ' . $data[0]['PK_Route'] ;
    	Yii::$app->db->createCommand($query)->execute();

    	$query = 'update "Route" set "IsLast" = true where "PK_Route" = ' . $data[count($data) - 1]['PK_Route'] ;
    	Yii::$app->db->createCommand($query)->execute();


    	return $this->actionViewtrip($id);
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

		return $this->actionAddroute($PK_Trip);
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

	public function actionViewallrequest($id)
	{
		$query = RequestLine::find()->where(['PK_Request' => $id]);
        $dataProvider = new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
		        'pageSize' => 20,
	    	],
		]);

        return $this->render('viewallrequest', [
			'requestModel' => Request::find()->where(["PK_Request" => $id])->one(),
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

	public function actionShiptypes() {
		$query = ShipType::find();

		$dataProvider = new ActiveDataProvider([
		    'query' => $query,
	    	'pagination' => [
	        'pageSize' => 20,
	    	],
		]);

		return $this->render('shiptypes', [
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

        return $this->render('ship/createship', [
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

        return $this->render('ship/updateship', [
            'model' => $model,
        ]);
	}

	/*

	Работа с типом корабля: добавление, удаление, изменение

	*/
	// Добавить корабль
    public function actionCreateshiptype()
    {
        $model = new ShipType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('shiptypes');
        }

        return $this->render('shiptype/createshiptype', [
            'model' => $model,
        ]);
    }

    // Удалить корабль
    public function actionDeleteshiptype($id)
    {
        $query = 'delete from "ShipType" where "PK_ShipType" = '. $id .';';
        $data = Yii::$app->db->createCommand($query)->queryOne();

        return $this->redirect(['shiptypes']);
    }

    // Изменить корабль
    public function actionUpdateshiptype($id)
    {
        $model = ShipType::find()->where(['PK_ShipType' => $id])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('shiptypes');
        }

        return $this->render('shiptype/updateshiptype', [
            'model' => $model,
        ]);
    }

}
