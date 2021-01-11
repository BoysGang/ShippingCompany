<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Route".
 *
 * @property int $PK_Route
 * @property string|null $TimeDuration
 * @property bool|null $IsFirst
 * @property bool|null $IsLast
 * @property bool|null $State
 * @property int $PK_Trip
 * @property int $PK_PortReceive
 * @property int $PK_PortSend
 *
 * @property Port $pKPortReceive
 * @property Port $pKPortSend
 * @property Trip $pKTrip
 */
class Route extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Route';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['TimeDuration'], 'string'],
            [['IsFirst', 'IsLast', 'State'], 'boolean'],
            [['PK_Trip', 'PK_PortReceive', 'PK_PortSend'], 'required'],
            [['PK_Trip', 'PK_PortReceive', 'PK_PortSend'], 'default', 'value' => null],
            [['PK_Trip', 'PK_PortReceive', 'PK_PortSend'], 'integer'],
            [['PK_PortReceive'], 'exist', 'skipOnError' => true, 'targetClass' => Port::className(), 'targetAttribute' => ['PK_PortReceive' => 'PK_Port']],
            [['PK_PortSend'], 'exist', 'skipOnError' => true, 'targetClass' => Port::className(), 'targetAttribute' => ['PK_PortSend' => 'PK_Port']],
            [['PK_Trip'], 'exist', 'skipOnError' => true, 'targetClass' => Trip::className(), 'targetAttribute' => ['PK_Trip' => 'PK_Trip']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'PK_Route' => 'Pk Route',
            'TimeDuration' => 'Time Duration',
            'IsFirst' => 'Is First',
            'IsLast' => 'Is Last',
            'State' => 'State',
            'PK_Trip' => 'Pk Trip',
            'PK_PortReceive' => 'Pk Port Receive',
            'PK_PortSend' => 'Pk Port Send',
        ];
    }

    /**
     * Gets query for [[PKPortReceive]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPKPortReceive()
    {
        return $this->hasOne(Port::className(), ['PK_Port' => 'PK_PortReceive']);
    }

    /**
     * Gets query for [[PKPortSend]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPKPortSend()
    {
        return $this->hasOne(Port::className(), ['PK_Port' => 'PK_PortSend']);
    }

    /**
     * Gets query for [[PKTrip]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPKTrip()
    {
        return $this->hasOne(Trip::className(), ['PK_Trip' => 'PK_Trip']);
    }
}
