<?php

use yii\db\Migration;

/**
 * Class m210110_132309_add_user_role
 */
class m210110_132309_add_user_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'role', $this->string(50)->after('username'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
