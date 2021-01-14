<?php

namespace app\models;

use Yii;

use app\models\Ship;

/**
 * This is the model class for table "Trip".
 *
 * @property int $PK_Trip
 * @property float|null $Cost
 * @property float|null $UnitPrice
 * @property string|null $DateDeparture
 * @property string|null $DateArrival
 * @property int $PK_Ship
 *
 * @property Request[] $requests
 * @property Route[] $routes
 * @property Ship $pKShip
 */
class Trip extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Trip';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Cost', 'UnitPrice'], 'number'],
            [['DateDeparture', 'DateArrival'], 'safe'],
            [['PK_Ship'], 'required'],
            [['PK_Ship'], 'default', 'value' => null],
            [['PK_Ship'], 'integer'],
            [['PK_Ship'], 'exist', 'skipOnError' => true, 'targetClass' => Ship::className(), 'targetAttribute' => ['PK_Ship' => 'PK_Ship']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'PK_Trip' => 'Номер рейса',
            'Cost' => 'Стоимость рейса',
            'UnitPrice' => 'Цена за 1 кг. груза',
            'UnitPriceRubles' => 'Цена за 1кг груза',
            'DateDeparture' => 'Дата отправки',
            'DateArrival' => 'Дата прибытия',
            'PK_Ship' => 'Корабль',
            'ShipName' => 'Корабль',
            'FirstPort' => 'Порт отправки',
            'LastPort' => 'Порт назначения',
            'CostInRubles' => 'Общие затраты',
            'Profit' => "Доходы",
            'NetProfit' => "Прибыль",
        ];
    }

    /**
     * Gets query for [[Requests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Request::className(), ['PK_Trip' => 'PK_Trip']);
    }

    /**
     * Gets query for [[Routes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes()
    {
        return $this->hasMany(Route::className(), ['PK_Trip' => 'PK_Trip']);
    }

    /**
     * Gets query for [[PKShip]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getShipName()
    {
        $ship = $this->pKShip->ShipNumber . " " . $this->pKShip->ShipName;
        return $ship;
    }

    public function getPKShip()
    {
        return $this->hasOne(Ship::className(), ['PK_Ship' => 'PK_Ship']);
    }

    public function getUnitPriceRubles()
    {
        $strPrice = str_replace('?', ' руб.', $this->UnitPrice);
        return $strPrice;
    }

    public function getFirstPort()
    {
        $query = 'select p."PortName" from "Route" a
                    left join "Port" p on a."PK_PortSend" = p."PK_Port"
                    where a."PK_Trip" = '. $this->PK_Trip .' and a."IsFirst" = true';
        $data = Yii::$app->db->createCommand($query)->queryOne();
        return $data['PortName'];
    }

    public function getLastPort()
    {
        $query = 'select p."PortName" from "Route" a
                    left join "Port" p on a."PK_PortReceive" = p."PK_Port"
                    where a."PK_Trip" = '. $this->PK_Trip .' and a."IsLast" = true';
        $data = Yii::$app->db->createCommand($query)->queryOne();
        return $data['PortName'];
    }

    public function getActiveTrips()
    {
        return Yii::$app->db->createCommand('select * from "Trip" where "DateDeparture" - now() > cast(\'14 days\' as interval)')->queryAll();
    }

    public function getCostInRubles()
    {
        $strCost = str_replace('?', ' руб.', $this->Cost);
        return $strCost;
    }

    public function getProfit()
    {
        $query = 'select sum(con."TotalPrice") from "Request" r
                  left join "Consignment" con on r."PK_Request" = con."PK_Request"
                  where r."PK_Trip" =' . $this->PK_Trip . 'and r."RequestStatus" = \'Accepted\';';

        $data = Yii::$app->db->createCommand($query)->queryOne();

        if ($data['sum'] == null)
            return "0 руб.";

        $strCost = str_replace('?', ' руб.', $data['sum']);
        return $strCost;
    }

    public function getNetProfit()
    {
        $profit = $this->moneyToFloat($this->getProfit());
        $cost = $this->moneyToFloat($this->Cost);

        $query = "select moneysubstraction(money(" . $profit . "), money(" . $cost . "));";
        $data = Yii::$app->db->createCommand($query)->queryOne();

        $netProfit = str_replace('?', ' руб.', $data['moneysubstraction']);

        return $netProfit;
    }

    private function moneyToFloat($money)
    {
        // Это разные пробелы!
        $money = str_replace(['?', 'руб.', ' ', ' '], '', $money);
        $money = str_replace(',', '.', $money);
        $money = floatval($money);

        return $money;
    }
}
