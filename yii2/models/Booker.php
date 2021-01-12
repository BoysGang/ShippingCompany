<?php

namespace app\models;

use Yii;

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
            'PK_Booker' => 'Pk Booker',
            'FullName' => 'Full Name',
            'PersonnelNum' => 'Personnel Num',
            'Salary' => 'Salary',
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
}
