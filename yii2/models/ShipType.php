<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ShipType".
 *
 * @property int $PK_ShipType
 * @property string|null $ShipTypeName
 * @property float|null $CarryingCapacity
 * @property int|null $AmountCaptains
 * @property int|null $AmountCaptainHelpers
 * @property int|null $AmountCooks
 * @property int|null $AmountMechanics
 * @property int|null $AmountElectricians
 * @property int|null $AmountSailors
 * @property int|null $AmountRadioOperators
 *
 * @property Ship[] $ships
 */
class ShipType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ShipType';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CarryingCapacity'], 'number'],
            [['AmountCaptains', 'AmountCaptainHelpers', 'AmountCooks', 'AmountMechanics', 'AmountElectricians', 'AmountSailors', 'AmountRadioOperators'], 'default', 'value' => null],
            [['AmountCaptains', 'AmountCaptainHelpers', 'AmountCooks', 'AmountMechanics', 'AmountElectricians', 'AmountSailors', 'AmountRadioOperators'], 'integer'],
            [['ShipTypeName'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'PK_ShipType' => 'Pk Ship Type',
            'ShipTypeName' => 'Тип',
            'CarryingCapacity' => 'Грузоподъемность',
            'AmountCaptains' => 'Капитанов',
            'AmountCaptainHelpers' => 'Помощников капитана',
            'AmountCooks' => 'Поваров',
            'AmountMechanics' => 'Механиков',
            'AmountElectricians' => 'Электриков',
            'AmountSailors' => 'Матросов',
            'AmountRadioOperators' => 'Радиосвязистов',
        ];
    }

    /**
     * Gets query for [[Ships]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShips()
    {
        return $this->hasMany(Ship::className(), ['PK_ShipType' => 'PK_ShipType']);
    }
}
