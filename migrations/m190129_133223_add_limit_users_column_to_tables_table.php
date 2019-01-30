<?php

use yii\db\Migration;

/**
 * Handles adding limit_users to table `tables`.
 */
class m190129_133223_add_limit_users_column_to_tables_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tables', 'limit_users', $this->integer(2)->after("type")->defaultValue(2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tables', 'limit_users');
    }
}
