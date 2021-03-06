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
		        'pageSize' => 5,
		    	],
		]);

		$query = Dispatcher::find();
		$dataProviderDP = new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
		        'pageSize' => 5,
		    	],
		]);

		$query = Booker::find();
		$dataProviderBK = new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
		        'pageSize' => 5,
		    	],
		]);

        $query = CrewMember::find();
        $dataProviderCM = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
                ],
        ]);

		//рендер главной страницы
		return $this->render('index', [
			'dataProviderHR' => $dataProviderHR,
			'dataProviderDP' => $dataProviderDP,
			'dataProviderBK' => $dataProviderBK,
            'dataProviderCM' => $dataProviderCM,
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


    /*

    Работа с членами экипажа: добавление, удаление, изменение

    */
    //Добавить члена экипажа
    public function actionCreatecrewmember()
    {
        $model = new CrewMember();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('crewmember/createcrewmember', [
            'model' => $model,
        ]);
    }

    //удалить члена экипажа
    public function actionDeletecrewmember($id)
    {
        $query = 'delete from "CrewMember" where "PK_CrewMember" = '. $id .';';
        $data = Yii::$app->db->createCommand($query)->queryOne();

        return $this->redirect(['index']);
    }

    //изменить члена экипажа
    public function actionUpdatecrewmember($id)
    {
        $model = CrewMember::find()->where(['PK_CrewMember' => $id])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('crewmember/updatecrewmember', [
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
		        'pageSize' => 20,
		    	],
		]);
    	return $this->render('contracts',[
    		'dataProvider' => $dataProvider
    	]);
    }

    //история контрактов определенного моряка
    //
    public function actionContractstory($id)
    {
        $query = 'select "FullName" from "CrewMember" where "PK_CrewMember" = '. $id .';';
        $data = Yii::$app->db->createCommand($query)->queryOne();
        $fullName = $data['FullName'];
    	//получение источников данных для всех контрактов
		//с предварительным получением запросов
		$query = Contract::find()->where(['PK_CrewMember' => $id]);
		$dataProvider = new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
		        'pageSize' => 20,
		    	],
		]);
    	return $this->render('contractstory',[
    		'fullName' => $fullName,
    		'dataProvider' => $dataProvider,
    	]);
    }

    //Контракты с истекающими сроками
    //которые еще не были перезаключены
    public function actionExpiredcontracts()
    {
    	$query = 'select * from "Contract" c
    			where c."DateExpiration" - now() < cast(\'30 days\' as interval) and c."DateExpiration" >= now()
    			and c."DateExpiration" = (
    			select max("DateExpiration") from "Contract" con where con."PK_CrewMember" = c."PK_CrewMember")';

		$dataProvider = new ActiveDataProvider([
		    'query' => Contract::findBySql($query),
		    'pagination' => [
		        'pageSize' => 20,
		    	],
		]);

    	return $this->render('expiredcontracts',
    		['dataProvider' => $dataProvider]);
    }

    //перезаключение контракта
    //по его первичному ключу
    public function actionExtendcontract($id)
    {
    	$oldModel = Contract::find()->where(["PK_Contract" => $id])->one();
    	$model = new Contract();

        if ($model->load(Yii::$app->request->post())) {
            $curDate = date("Y-m-d");
            $userPK = (HREmployee::find()->Select('PK_HREmployee')->where(['PersonnelNum' => Yii::$app->user->identity->getUsername()])->one()->PK_HREmployee);
            $_POST = Yii::$app->request->post()['Contract'];

            $query = 'select createcontract(\''. $curDate . '\', ' .
                '\'' . $_POST['DateExpiration'] . '\',' .
                '\'' . $_POST['Salary'] . '\', ' .
                $_POST['PK_Ship'] . ', ' .
                $userPK . ', ' .
                $_POST['PK_CrewMember'] . ', ' .
                $_POST['PK_CrewPosition'] . ');';

            //выполнение запроса
            Yii::$app->db->createCommand($query)->execute();

            return $this->redirect('index');
        }

        return $this->render('extendcontract', [
            'model' => $model,
            'oldModel' => $oldModel,
        ]);
    }


    //создать новый контракт
    public function actionCreatecontract()
    {
    	$model = new Contract();

        if ($model->load(Yii::$app->request->post())) {

            $userPK = (HREmployee::find()->Select('PK_HREmployee')->where(['PersonnelNum' => Yii::$app->user->identity->getUsername()])->one()->PK_HREmployee);
            $_POST = Yii::$app->request->post()['Contract'];

            $query = 'select createcontract(\''. $_POST['DateConclusion'] . '\', ' .
                '\'' . $_POST['DateExpiration'] . '\',' .
                '\'' . $_POST['Salary'] . '\', ' .
                $_POST['PK_Ship'] . ', ' .
                $userPK . ', ' .
                $_POST['PK_CrewMember'] . ', ' .
                $_POST['PK_CrewPosition'] . ');';

            //выполнение запроса
            Yii::$app->db->createCommand($query)->execute();

            return $this->redirect('index');
        }

        return $this->render('createcontract', [
            'model' => $model,
        ]);
    }
}
