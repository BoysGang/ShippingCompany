<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "RequestLine".
 *
 * @property int $PK_RequestLine
 * @property string|null $CargoName
 * @property int|null $CargoAmount
 * @property int $PK_Request
 *
 * @property Request $pKRequest
 */
class RequestLine extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'RequestLine';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CargoAmount', 'PK_Request'], 'default', 'value' => null],
            [['CargoAmount', 'PK_Request'], 'integer'],
            [['PK_Request'], 'required'],
            [['CargoName'], 'string', 'max' => 100],
            [['PK_Request'], 'exist', 'skipOnError' => true, 'targetClass' => Request::className(), 'targetAttribute' => ['PK_Request' => 'PK_Request']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'PK_RequestLine' => 'Pk Request Line',
            'CargoName' => 'Груз',
            'CargoAmount' => 'Масса груза, кг',
            'PK_Request' => 'Pk Request',
        ];
    }

    /**
     * Gets query for [[PKRequest]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPKRequest()
    {
        return $this->hasOne(Request::className(), ['PK_Request' => 'PK_Request']);
    }
}
