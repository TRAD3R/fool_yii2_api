<?php

use yii\db\Migration;

/**
 * Handles the creation of table `ranks`.
 */
class m190127_115720_create_ranks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('ranks', [
            'id' => $this->primaryKey(),
            'rank' => $this->string(10)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('ranks');
    }
}
