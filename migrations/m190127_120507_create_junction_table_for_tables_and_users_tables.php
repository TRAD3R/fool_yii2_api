<?php

use yii\db\Migration;

/**
 * Handles the creation of table `tables_users`.
 * Has foreign keys to the tables:
 *
 * - `tables`
 * - `users`
 */
class m190127_120507_create_junction_table_for_tables_and_users_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('games', [
            'id' => $this->primaryKey(),
            'table_id' => $this->integer(),
            'user_id' => $this->integer(),
        ]);

        // creates index for column `tables_id`
        $this->createIndex(
            'idx-games-table_id',
            'games',
            'table_id'
        );

        // add foreign key for table `tables`
        $this->addForeignKey(
            'fk-games-table_id',
            'games',
            'table_id',
            'tables',
            'id',
            'CASCADE'
        );

        // creates index for column `users_id`
        $this->createIndex(
            'idx-games-user_id',
            'games',
            'user_id'
        );

        // add foreign key for table `users`
        $this->addForeignKey(
            'fk-games-user_id',
            'games',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `tables`
        $this->dropForeignKey(
            'fk-games-table_id',
            'games'
        );

        // drops index for column `tables_id`
        $this->dropIndex(
            'idx-games-table_id',
            'games'
        );

        // drops foreign key for table `users`
        $this->dropForeignKey(
            'fk-games-user_id',
            'games'
        );

        // drops index for column `users_id`
        $this->dropIndex(
            'idx-games-user_id',
            'games'
        );

        $this->dropTable('games');
    }
}
