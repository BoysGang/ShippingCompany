<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Request".
 *
 * @property int $PK_Request
 * @property string|null $RequestNum
 * @property int $PK_Sender
 * @property int $PK_Receiver
 * @property int $PK_Trip
 * @property int $PK_PortReceive
 * @property int $PK_PortSend
 * @property string|null $RequestStatus
 *
 * @property Consignment[] $consignments
 * @property Client $pKSender
 * @property Client $pKReceiver
 * @property Port $pKPortReceive
 * @property Port $pKPortSend
 * @property Trip $pKTrip
 * @property RequestLine[] $requestLines
 */
class Request extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['PK_Sender', 'PK_Receiver', 'PK_Trip', 'PK_PortReceive', 'PK_PortSend'], 'required'],
            [['PK_Sender', 'PK_Receiver', 'PK_Trip', 'PK_PortReceive', 'PK_PortSend'], 'default', 'value' => null],
            [['PK_Sender', 'PK_Receiver', 'PK_Trip', 'PK_PortReceive', 'PK_PortSend'], 'integer'],
            [['RequestNum', 'RequestStatus'], 'string', 'max' => 50],
            [['PK_Sender'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['PK_Sender' => 'PK_Client']],
            [['PK_Receiver'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['PK_Receiver' => 'PK_Client']],
            [['PK_PortReceive'], 'exist', 'skipOnError' => true, 'targetClass' => Port::className(), 'targetAttribute' => ['PK_PortReceive' => 'PK_Port']],
            [['PK_PortSend'], 'exist', 'skipOnError' => true, 'targetClass' => Port::className(), 'targetAttribute' => ['PK_PortSend' => 'PK_Port']],
            [['PK_Trip'], 'exist', 'skipOnError' => true, 'targetClass' => Trip::className(), 'targetAttribute' => ['PK_Trip' => 'PK_Trip']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'PK_Request' => 'Pk Request',
            'RequestNum' => 'Номер заявки',
            'PK_Sender' => 'PK_Sender',
            'PK_Receiver' => 'Получатель',
            'PK_Trip' => 'Рейс',
            'PK_PortReceive' => 'Порт назначения',
            'PK_PortSend' => 'Порт отправления',
            'RequestStatus' => 'Статус заявки',
        ];
    }

    /**
     * Gets query for [[Consignments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConsignments()
    {
        return $this->hasMany(Consignment::className(), ['PK_Request' => 'PK_Request']);
    }

    /**
     * Gets query for [[PKSender]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPKSender()
    {
        return $this->hasOne(Client::className(), ['PK_Client' => 'PK_Sender']);
    }

    //получение имени клиента
    public function getReceiverFullName()
    {
        return $this->pKReceiver->FullName;
    }
    /**
     * Gets query for [[PKReceiver]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPKReceiver()
    {
        return $this->hasOne(Client::className(), ['PK_Client' => 'PK_Receiver']);
    }

    /**
     * Gets query for [[PKPortReceive]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPKPortReceive()
    {
        return $this->hasOne(Port::className(), ['PK_Port' => 'PK_PortReceive']);
    }

    public function getPortReceiveName()
    {
        return $this->pKPortReceive->PortName;
    }

    /**
     * Gets query for [[PKPortSend]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPKPortSend()
    {
        return $this->hasOne(Port::className(), ['PK_Port' => 'PK_PortSend']);
    }

    //наименование порта отправления
    public function getPortSendName()
    {
        return $this->pKPortSend->PortName;
    }

    /**
     * Gets query for [[PKTrip]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPKTrip()
    {
        return $this->hasOne(Trip::className(), ['PK_Trip' => 'PK_Trip']);
    }

    /**
     * Gets query for [[RequestLines]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestLines()
    {
        return $this->hasMany(RequestLine::className(), ['PK_Request' => 'PK_Request']);
    }
}
