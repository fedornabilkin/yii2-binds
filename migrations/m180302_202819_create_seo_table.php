<?php

use yii\db\Migration;

/**
 * Handles the creation of table `seo`.
 */
class m180302_202819_create_seo_table extends Migration
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
        $this->createTable('{{%bind_seo}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->bigInteger()->notNull()->unsigned()->unique(),
//            'uid_content' => $this->bigInteger()->unsigned()->unique(),
            'title' => $this->char(150),
            'keywords' => $this->char(150),
            'description' => $this->char(150),
            'alias' => $this->char(150)->unique(),
            'h1' => $this->char(150),
        ], $tableOptions);

//        $this->createIndex('{{%idx-bind_seo-alias}}','{{%bind_seo}}','alias');
//        $this->createIndex('{{%idx-bind_seo-uid}}','{{%bind_seo}}','uid');

        $this->addForeignKey('fki-bind_seo-uid-bind_uids-id',
            '{{%bind_seo}}',
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
        $this->dropForeignKey('fki-bind_seo-uid-bind_uids-id', '{{%bind_seo}}');
//        $this->dropIndex('{{%idx-bind_seo-alias}}','{{%bind_seo}}');
//        $this->dropIndex('{{%idx-bind_seo-uid_content}}','{{%bind_seo}}');

        $this->dropTable('{{%bind_seo}}');
    }
}
