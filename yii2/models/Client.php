<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Client".
 *
 * @property int $PK_Client
 * @property string|null $FullName
 *
 * @property Request[] $requests
 * @property Request[] $requests0
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Client';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['FullName'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'PK_Client' => 'Pk Client',
            'FullName' => 'Full Name',
        ];
    }

    /**
     * Gets query for [[Requests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Request::className(), ['PK_Sender' => 'PK_Client']);
    }

    /**
     * Gets query for [[Requests0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests0()
    {
        return $this->hasMany(Request::className(), ['PK_Receiver' => 'PK_Client']);
    }
}
