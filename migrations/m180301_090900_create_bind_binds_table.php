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
            'id' => $this->primaryKey()->unsigned(),
            'uid' => $this->bigInteger()->notNull()->unsigned(),
            'uid_bind' => $this->bigInteger()->notNull()->unsigned(),
        ], $tableOptions);

        $this->createIndex('{{%idx-bind_binds-uid}}','{{%bind_binds}}','uid');
        $this->createIndex('{{%idx-bind_binds-uid_bind}}','{{%bind_binds}}','uid_bind');
        $this->createIndex('{{%idx-bind_binds-uid_uid_bind}}','{{%bind_binds}}', ['uid', 'uid_bind'], true);

        $this->addForeignKey('fki-bind_binds-uid-bind_uids-id',
            '{{%bind_binds}}',
            'uid',
            '{{%bind_uids}}',
            'id',
            'CASCADE');

        $this->addForeignKey('fki-bind_binds-uid_bind-bind_uids-id',
            '{{%bind_binds}}',
            'uid_bind',
            '{{%bind_uids}}',
            'id',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fki-bind_binds-uid-bind_uids-id', '{{%bind_binds}}');
        $this->dropForeignKey('fki-bind_binds-uid_bind-bind_uids-id', '{{%bind_binds}}');

        $this->dropIndex('{{%idx-bind_binds-uid}}','{{%bind_binds}}');
        $this->dropIndex('{{%idx-bind_binds-uid_bind}}','{{%bind_binds}}');
        $this->dropIndex('{{%idx-bind_binds-uid_uid_bind}}','{{%bind_binds}}');

        $this->dropTable('bind_binds');
    }
}
