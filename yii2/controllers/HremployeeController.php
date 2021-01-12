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
use app\models\Contract;
use app\models\CrewMember;

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


	/*

	Работа с бухгалтером: добавление, удаление, изменение

	*/
	//Добавить бухгалтера
    public function actionCreatebooker()
    {
        $model = new Booker();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('booker/createbooker', [
            'model' => $model,
        ]);
    }

    //удалить бухгалтера
    public function actionDeletebooker($id)
    {
        $query = 'delete from "Booker" where "PK_Booker" = '. $id .';';
        $data = Yii::$app->db->createCommand($query)->queryOne();

        return $this->redirect(['index']);
    }

    //изменить бухгалтера
    public function actionUpdatebooker($id)
    {
        $model = Booker::find()->where(['PK_Booker' => $id])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('booker/updatebooker', [
            'model' => $model,
        ]);
    }




	/*

	Работа с диспетчером: добавление, удаление, изменение

	*/
	//Добавить диспетчера
    public function actionCreatedispatcher()
    {
        $model = new Dispatcher();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('dispatcher/createdispatcher', [
            'model' => $model,
        ]);
    }

    //удалить бухгалтера
    public function actionDeletedispatcher($id)
    {
        $query = 'delete from "Dispatcher" where "PK_Dispatcher" = '. $id .';';
        $data = Yii::$app->db->createCommand($query)->queryOne();

        return $this->redirect(['index']);
    }

    //изменить диспетчера
    public function actionUpdatedispatcher($id)
    {
        $model = Dispatcher::find()->where(['PK_Dispatcher' => $id])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('dispatcher/updatedispatcher', [
            'model' => $model,
        ]);
    }




	/*

	Работа с кадровиком: добавление, удаление, изменение

	*/
	//Добавить кадровика
    public function actionCreatehremployee()
    {
        $model = new HREmployee();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('hremployee/createhremployee', [
            'model' => $model,
        ]);
    }

    //удалить кадровика
    public function actionDeletehremployee($id)
    {
        $query = 'delete from "HREmployee" where "PK_HREmployee" = '. $id .';';
        $data = Yii::$app->db->createCommand($query)->queryOne();

        return $this->redirect(['index']);
    }

    //изменить кадровика
    public function actionUpdatehremployee($id)
    {
        $model = HREmployee::find()->where(['PK_HREmployee' => $id])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('hremployee/updatehremployee', [
            'model' => $model,
        ]);
    }


    public function actionContracts()
    {
    	//получение источников данных для всех контрактов
		//с предварительным получением запросов
		$query = Contract::find();
		$dataProvider = new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
		        'pageSize' => 10,
		    	],
		]);
    	return $this->render('contracts',[
    		'dataProvider' => $dataProvider
    	]);
    }

    //история контрактов определенного моряка
    //
    public function actioncontractstory($id)
    {
    	$fullName = CrewMember::find()->select('CrewMember.FullName')->where('PK_CrewMember' => $id)->one();
    	$this->console_log($fullName);
    	//получение источников данных для всех контрактов
		//с предварительным получением запросов
		$query = Contract::find()->where('PK_CrewMember' => $id);
		$dataProvider = new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
		        'pageSize' => 10,
		    	],
		]);
    	return $this->render('contracts',[
    		'fullName' => $fullName,
    		'dataProvider' => $dataProvider,
    	]);
    }

    public function actionExpiredcontracts()
    {

    	return $this->render('expiredcontracts');
    }
}
