<?php

use yii\db\Migration;

/**
 * Handles the creation of table `suits`.
 */
class m190127_123023_create_suits_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('suits', [
            'id' => $this->primaryKey(),
            'suit' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('suits');
    }
}
