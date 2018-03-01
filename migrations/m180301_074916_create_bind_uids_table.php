<?php

use yii\db\Migration;

/**
 * Handles the creation of table `bind_uids`.
 */
class m180301_074916_create_bind_uids_table extends Migration
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

        $this->createTable('{{%bind_uids}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->integer(11)->notNull(),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(1),
            'id_user' => $this->integer(11)->notNull()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('{{%idx-bind_uids-uid}}','{{%bind_uids}}','uid',true);
        $this->createIndex('{{%idx-bind_uids-id_user}}','{{%bind_uids}}','id_user');
        $this->createIndex('{{%idx-bind_uids-status}}','{{%bind_uids}}','status');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('{{%idx-bind_uids-uid}}','{{%bind_uids}}');
        $this->dropIndex('{{%idx-bind_uids-id_user}}','{{%bind_uids}}');
        $this->dropIndex('{{%idx-bind_uids-status}}','{{%bind_uids}}');

        $this->dropTable('{{%bind_uids}}');
    }
}
