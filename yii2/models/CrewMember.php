<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "CrewMember".
 *
 * @property int $PK_CrewMember
 * @property string|null $FullName
 *
 * @property Contract[] $contracts
 */
class CrewMember extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CrewMember';
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
            'PK_CrewMember' => 'Член экипажа',
            'FullName' => 'Полное имя',
        ];
    }

    /**
     * Gets query for [[Contracts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContracts()
    {
        return $this->hasMany(Contract::className(), ['PK_CrewMember' => 'PK_CrewMember']);
    }
}
