<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Consignment".
 *
 * @property int $PK_Consignment
 * @property string|null $BookingDate
 * @property float|null $TotalPrice
 * @property int $PK_Request
 * @property int|null $PK_Booker
 * @property int|null $PK_Dispatcher
 *
 * @property Booker $pKBooker
 * @property Dispatcher $pKDispatcher
 * @property Request $pKRequest
 */
class Consignment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Consignment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['BookingDate'], 'safe'],
            [['TotalPrice'], 'number'],
            [['PK_Request'], 'required'],
            [['PK_Request', 'PK_Booker', 'PK_Dispatcher'], 'default', 'value' => null],
            [['PK_Request', 'PK_Booker', 'PK_Dispatcher'], 'integer'],
            [['PK_Booker'], 'exist', 'skipOnError' => true, 'targetClass' => Booker::className(), 'targetAttribute' => ['PK_Booker' => 'PK_Booker']],
            [['PK_Dispatcher'], 'exist', 'skipOnError' => true, 'targetClass' => Dispatcher::className(), 'targetAttribute' => ['PK_Dispatcher' => 'PK_Dispatcher']],
            [['PK_Request'], 'exist', 'skipOnError' => true, 'targetClass' => Request::className(), 'targetAttribute' => ['PK_Request' => 'PK_Request']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'PK_Consignment' => 'Номер коносамента',
            'BookingDate' => 'Дата бронирования',
            'TotalPrice' => 'Общая стоимость',
            'PK_Request' => 'Номер заявки',
            'PK_Booker' => 'Бухгалтер',
            'PK_Dispatcher' => 'Диспетчер',
        ];
    }

    /**
     * Gets query for [[PKBooker]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPKBooker()
    {
        return $this->hasOne(Booker::className(), ['PK_Booker' => 'PK_Booker']);
    }

    /**
     * Gets query for [[PKDispatcher]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPKDispatcher()
    {
        return $this->hasOne(Dispatcher::className(), ['PK_Dispatcher' => 'PK_Dispatcher']);
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

    public function getTotalPriceInRubles()
    {
        $strCost = str_replace('?', ' руб.', $this->TotalPrice);
        return $strCost;
    }
}
