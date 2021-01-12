<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Ship".
 *
 * @property int $PK_Ship
 * @property string|null $ShipNumber
 * @property string|null $ShipName
 * @property int $PK_ShipType
 *
 * @property Contract[] $contracts
 * @property ShipType $pKShipType
 * @property Trip[] $trips
 */
class Ship extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Ship';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['PK_ShipType'], 'required'],
            [['PK_ShipType'], 'default', 'value' => null],
            [['PK_ShipType'], 'integer'],
            [['ShipNumber'], 'string', 'max' => 30],
            [['ShipName'], 'string', 'max' => 100],
            [['PK_ShipType'], 'exist', 'skipOnError' => true, 'targetClass' => ShipType::className(), 'targetAttribute' => ['PK_ShipType' => 'PK_ShipType']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'PK_Ship' => 'Корабль',
            'ShipNumber' => 'Ship Number',
            'ShipName' => 'Название корабля',
            'PK_ShipType' => 'Pk Ship Type',
        ];
    }

    /**
     * Gets query for [[Contracts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContracts()
    {
        return $this->hasMany(Contract::className(), ['PK_Ship' => 'PK_Ship']);
    }

    public function getShipNumName()
    {
        return $this->ShipName . " - " . $this->ShipNumber;
    }

    /**
     * Gets query for [[PKShipType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPKShipType()
    {
        return $this->hasOne(ShipType::className(), ['PK_ShipType' => 'PK_ShipType']);
    }

    /**
     * Gets query for [[Trips]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrips()
    {
        return $this->hasMany(Trip::className(), ['PK_Ship' => 'PK_Ship']);
    }
}
