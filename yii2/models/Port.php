<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Port".
 *
 * @property int $PK_Port
 * @property string|null $PortName
 *
 * @property Request[] $requests
 * @property Request[] $requests0
 * @property Route[] $routes
 * @property Route[] $routes0
 */
class Port extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Port';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['PortName'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'PK_Port' => 'Pk Port',
            'PortName' => 'Port Name',
        ];
    }

    /**
     * Gets query for [[Requests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Request::className(), ['PK_PortReceive' => 'PK_Port']);
    }

    /**
     * Gets query for [[Requests0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests0()
    {
        return $this->hasMany(Request::className(), ['PK_PortSend' => 'PK_Port']);
    }

    /**
     * Gets query for [[Routes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes()
    {
        return $this->hasMany(Route::className(), ['PK_PortReceive' => 'PK_Port']);
    }

    /**
     * Gets query for [[Routes0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes0()
    {
        return $this->hasMany(Route::className(), ['PK_PortSend' => 'PK_Port']);
    }
}
