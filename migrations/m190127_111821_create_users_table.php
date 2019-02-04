<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m190127_111821_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'email' => $this->string(50)->unique()->notNull(),
            'pass_hash' => $this->string(80)->unique()->notNull(),
            'auth_key' => $this->string(100)->unique()->notNull(),
            'resource_id' => $this->integer()->unique(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users');
    }
}
