<?php

use yii\db\Migration;

/**
 * Handles the creation of table `catalog`.
 */
class m180308_163510_create_catalog_table extends Migration
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
        $this->createTable('{{%bind_catalog}}', [
            'id' => $this->primaryKey(),
            'uid'=> $this->bigInteger()->notNull()->unsigned()->unique(),
            'alias'=> $this->char(60)->unique(),
            'nickname'=> $this->char(60)->unique(),
            'root' => $this->integer(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'lvl' => $this->smallInteger(5)->notNull(),
            'name' => $this->string(60)->notNull(),
            'icon' => $this->string(255),
            'icon_type' => $this->smallInteger(1)->notNull()->defaultValue(1),
            'active' => $this->boolean()->notNull()->defaultValue(true),
            'selected' => $this->boolean()->notNull()->defaultValue(false),
            'disabled' => $this->boolean()->notNull()->defaultValue(false),
            'readonly' => $this->boolean()->notNull()->defaultValue(false),
            'visible' => $this->boolean()->notNull()->defaultValue(true),
            'collapsed' => $this->boolean()->notNull()->defaultValue(false),
            'movable_u' => $this->boolean()->notNull()->defaultValue(true),
            'movable_d' => $this->boolean()->notNull()->defaultValue(true),
            'movable_l' => $this->boolean()->notNull()->defaultValue(true),
            'movable_r' => $this->boolean()->notNull()->defaultValue(true),
            'removable' => $this->boolean()->notNull()->defaultValue(true),
            'removable_all' => $this->boolean()->notNull()->defaultValue(false)
        ],$tableOptions);

        $this->createIndex('{{%idx-bind_catalog-uid}}','{{%bind_catalog}}','uid');
        $this->createIndex('{{%idx-bind_catalog-alias}}','{{%bind_catalog}}','alias');
        $this->createIndex('{{%idx-bind_catalog-nickname}}','{{%bind_catalog}}','nickname');

        $this->createIndex('{{%idx-bind_catalog-root}}','{{%bind_catalog}}','root');
        $this->createIndex('{{%idx-bind_catalog-lft}}','{{%bind_catalog}}','lft');
        $this->createIndex('{{%idx-bind_catalog-rgt}}','{{%bind_catalog}}','rgt');
        $this->createIndex('{{%idx-bind_catalog-lvl}}','{{%bind_catalog}}','lvl');
        $this->createIndex('{{%idx-bind_catalog-active}}','{{%bind_catalog}}','active');

        $this->addForeignKey('fki-bind_catalog-uid-bind_uids-id',
            '{{%bind_catalog}}',
            'uid',
            '{{%bind_uids}}',
            'id',
            'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fki-bind_seo-uid-bind_uids-id', '{{%bind_catalog}}');

        $this->dropIndex('{{%idx-bind_seo-uid}}','{{%bind_catalog}}');
        $this->dropIndex('{{%idx-bind_seo-alias}}','{{%bind_catalog}}');
        $this->dropIndex('{{%idx-bind_seo-nickname}}','{{%bind_catalog}}');
        $this->dropIndex('{{%idx-bind_seo-root}}','{{%bind_catalog}}');
        $this->dropIndex('{{%idx-bind_seo-lft}}','{{%bind_catalog}}');
        $this->dropIndex('{{%idx-bind_seo-rgt}}','{{%bind_catalog}}');
        $this->dropIndex('{{%idx-bind_seo-lvl}}','{{%bind_catalog}}');
        $this->dropIndex('{{%idx-bind_seo-active}}','{{%bind_catalog}}');

        $this->dropTable('{{%bind_catalog}}');
    }
}
