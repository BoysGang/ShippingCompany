<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Dispatcher".
 *
 * @property int $PK_Dispatcher
 * @property string|null $FullName
 * @property string|null $PersonnelNum
 * @property float|null $Salary
 *
 * @property Consignment[] $consignments
 */
class Dispatcher extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Dispatcher';
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
            'PK_Dispatcher' => 'Pk Dispatcher',
            'FullName' => 'Полное имя',
            'PersonnelNum' => 'Табельный номер',
            'Salary' => 'Заработная плата',
            'SalaryRubles' => 'Заработная плата',
        ];
    }

    /**
     * Gets query for [[Consignments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConsignments()
    {
        return $this->hasMany(Consignment::className(), ['PK_Dispatcher' => 'PK_Dispatcher']);
    }

    public function getSalaryRubles()
    {
        $strPrice = str_replace('?', ' руб.', $this->Salary);
        return $strPrice;
    }

    public function getPersonnelnum()
    {
        return $this->PersonnelNum;
    }
}
