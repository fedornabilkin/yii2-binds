<?php

use yii\db\Migration;

/**
 * Handles the creation of table `bind_binds`.
 */
class m180301_090900_create_bind_binds_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bind_binds}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->bigInteger()->notNull(),
            'uid_bind' => $this->bigInteger()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-bind_binds-uid}}','{{%bind_binds}}','uid');
        $this->createIndex('{{%idx-bind_binds-uid_bind}}','{{%bind_binds}}','uid_bind');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx-bind_binds-uid}}','{{%bind_binds}}');
        $this->dropIndex('{{%idx-bind_binds-uid_bind}}','{{%bind_binds}}');

        $this->dropTable('bind_binds');
    }
}