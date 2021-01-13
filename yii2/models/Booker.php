<?php

namespace app\models;

use Yii;
use yii\validators\UniqueValidator;

/**
 * This is the model class for table "Booker".
 *
 * @property int $PK_Booker
 * @property string|null $FullName
 * @property string|null $PersonnelNum
 * @property float|null $Salary
 *
 * @property Consignment[] $consignments
 */
class Booker extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Booker';
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
            'PK_Booker' => 'Бухгалтер',
            'FullName' => 'Полное имя',
            'PersonnelNum' => 'Табельный номер',
            'Salary' => 'Salary',
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
        return $this->hasMany(Consignment::className(), ['PK_Booker' => 'PK_Booker']);
    }


    public function getSalaryRubles()
    {
        $strPrice = str_replace('?', ' руб.', $this->Salary);
        return $strPrice;
    }

    public function getFullname()
    {
        return $this->FullName;
    }
}
