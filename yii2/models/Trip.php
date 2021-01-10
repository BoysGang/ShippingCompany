<?php

namespace app\models;

use Yii;

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
            'PK_Trip' => 'Pk Trip',
            'Cost' => 'Cost',
            'UnitPrice' => 'Unit Price',
            'DateDeparture' => 'Date Departure',
            'DateArrival' => 'Date Arrival',
            'PK_Ship' => 'Pk Ship',
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
    public function getPKShip()
    {
        return $this->hasOne(Ship::className(), ['PK_Ship' => 'PK_Ship']);
    }
}
