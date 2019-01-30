<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tables`.
 */
class m190127_113512_create_tables_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tables', [
            'id' => $this->primaryKey(),
            'type' => $this->integer(1)->defaultValue(1)->comment("0 - close, 1 - open"),
            'created' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('tables');
    }
}
