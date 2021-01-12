<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Contract".
 *
 * @property int $PK_Contract
 * @property string|null $DateConclusion
 * @property string|null $DateExpiration
 * @property float|null $Salary
 * @property int $PK_Ship
 * @property int $PK_HREmployee
 * @property int $PK_CrewMember
 * @property int $PK_CrewPosition
 *
 * @property CrewMember $pKCrewMember
 * @property CrewPosition $pKCrewPosition
 * @property HREmployee $pKHREmployee
 * @property Ship $pKShip
 */
class Contract extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Contract';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['DateConclusion', 'DateExpiration'], 'safe'],
            [['Salary'], 'number'],
            [['PK_Ship', 'PK_HREmployee', 'PK_CrewMember', 'PK_CrewPosition'], 'required'],
            [['PK_Ship', 'PK_HREmployee', 'PK_CrewMember', 'PK_CrewPosition'], 'default', 'value' => null],
            [['PK_Ship', 'PK_HREmployee', 'PK_CrewMember', 'PK_CrewPosition'], 'integer'],
            [['PK_CrewMember'], 'exist', 'skipOnError' => true, 'targetClass' => CrewMember::className(), 'targetAttribute' => ['PK_CrewMember' => 'PK_CrewMember']],
            [['PK_CrewPosition'], 'exist', 'skipOnError' => true, 'targetClass' => CrewPosition::className(), 'targetAttribute' => ['PK_CrewPosition' => 'PK_CrewPosition']],
            [['PK_HREmployee'], 'exist', 'skipOnError' => true, 'targetClass' => HREmployee::className(), 'targetAttribute' => ['PK_HREmployee' => 'PK_HREmployee']],
            [['PK_Ship'], 'exist', 'skipOnError' => true, 'targetClass' => Ship::className(), 'targetAttribute' => ['PK_Ship' => 'PK_Ship']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'PK_Contract' => 'Pk Contract',
            'DateConclusion' => 'Date Conclusion',
            'DateExpiration' => 'Date Expiration',
            'Salary' => 'Salary',
            'PK_Ship' => 'Pk Ship',
            'PK_HREmployee' => 'Pk Hr Employee',
            'PK_CrewMember' => 'Pk Crew Member',
            'PK_CrewPosition' => 'Pk Crew Position',
        ];
    }

    /**
     * Gets query for [[PKCrewMember]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPKCrewMember()
    {
        return $this->hasOne(CrewMember::className(), ['PK_CrewMember' => 'PK_CrewMember']);
    }

    /**
     * Gets query for [[PKCrewPosition]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPKCrewPosition()
    {
        return $this->hasOne(CrewPosition::className(), ['PK_CrewPosition' => 'PK_CrewPosition']);
    }

    /**
     * Gets query for [[PKHREmployee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPKHREmployee()
    {
        return $this->hasOne(HREmployee::className(), ['PK_HREmployee' => 'PK_HREmployee']);
    }

    /**
     * Gets query for [[PKShip]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPKShip()
    {
        return $this->hasOne(Ship::className(), ['PK_Ship' => 'PK_Ship']);
    }
}
