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
            [['FullName', 'PersonnelNum', 'Salary'], 'required'],
            [['Salary'], 'number'],
            [['FullName'], 'string', 'max' => 100],
            [['PersonnelNum'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'PK_HREmployee' => 'Pk Hr Employee',
            'FullName' => 'Полное имя',
            'PersonnelNum' => 'Табельный номер',
            'Salary' => 'Заработная плата',
            'SalaryRubles' => 'Заработная плата'
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

    public function getSalaryRubles()
    {
        $strPrice = str_replace('?', ' руб.', $this->Salary);
        return $strPrice;
    }
}
