<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Request;

/**
 * RequestSearch represents the model behind the search form of `app\models\Request`.
 */
class RequestSearch extends Request
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['PK_Request', 'PK_Sender', 'PK_Receiver', 'PK_Trip', 'PK_PortReceive', 'PK_PortSend'], 'integer'],
            [['RequestNum', 'RequestStatus'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Request::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'PK_Request' => $this->PK_Request,
            'PK_Sender' => $this->PK_Sender,
            'PK_Receiver' => $this->PK_Receiver,
            'PK_Trip' => $this->PK_Trip,
            'PK_PortReceive' => $this->PK_PortReceive,
            'PK_PortSend' => $this->PK_PortSend,
        ]);

        $query->andFilterWhere(['ilike', 'RequestNum', $this->RequestNum])
            ->andFilterWhere(['ilike', 'RequestStatus', $this->RequestStatus]);

        return $dataProvider;
    }
}
