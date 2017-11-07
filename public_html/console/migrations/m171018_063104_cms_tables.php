<?php

use yii\db\Migration;

class m171018_063104_cms_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('cms', [
            'id' => $this->primaryKey()->unsigned(),
            'techname' => $this->string()->notNull()->unique(),
        ], $tableOptions);

        $this->createTable('cms_text', [
            'id' => $this->primaryKey()->unsigned(),
            'languages_id' => $this->integer(10)->unsigned()->notNull(),
            'cms_id' => $this->integer(10)->unsigned()->notNull(),
            'url' => $this->string()->notNull(),
            'seo_title' => $this->string()->notNull(),
            'seo_h2' => $this->string(),
            'seo_desc' => $this->text(),
            'seo_keywords' => $this->string(),
            'seo_text' => $this->text()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx_cms_languages_id', 'cms_text', 'languages_id');
        $this->addForeignKey('cms_text_ibfk_1', 'cms_text', 'languages_id', 'languages', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('idx_ctid_id', 'cms_text', 'cms_id');
        $this->addForeignKey('fk_cms_id', 'cms_text', 'cms_id', 'cms', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('cms_text_ibfk_1','cms_text');
        $this->dropIndex('idx_cms_languages_id','cms_text');
        $this->dropForeignKey('fk_cms_id','cms');
        $this->dropIndex('idx_ctid_id','cms');

        $this->dropTable('cms_text');
        $this->dropTable('cms');
    }
}
