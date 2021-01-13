<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "CrewPosition".
 *
 * @property int $PK_CrewPosition
 * @property string|null $CrewPositionName
 *
 * @property Contract[] $contracts
 */
class CrewPosition extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CrewPosition';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CrewPositionName'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'PK_CrewPosition' => 'Должность',
            'CrewPositionName' => 'Должность',
        ];
    }

    /**
     * Gets query for [[Contracts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContracts()
    {
        return $this->hasMany(Contract::className(), ['PK_CrewPosition' => 'PK_CrewPosition']);
    }
}
