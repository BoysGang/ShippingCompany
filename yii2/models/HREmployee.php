<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "HREmployee".
 *
 * @property int $PK_HREmployee
 * @property string|null $FullName
 * @property string|null $PersonnelNum
 * @property float|null $Salary
 *
 * @property Contract[] $contracts
 */
class HREmployee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'HREmployee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Salary'], 'number'],
            [['FullName'], 'string', 'max' => 100],
            [['PersonnelNum'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'PK_HREmployee' => 'Pk Hr Employee',
            'FullName' => 'Full Name',
            'PersonnelNum' => 'Personnel Num',
            'Salary' => 'Salary',
        ];
    }

    /**
     * Gets query for [[Contracts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContracts()
    {
        return $this->hasMany(Contract::className(), ['PK_HREmployee' => 'PK_HREmployee']);
    }
}
