<?php

use yii\db\Migration;

class m161126_233728_categories_text_table_create extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {

            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('categories_text', [
            'id' => $this->primaryKey()->unsigned(),
            'categories_id' => $this->integer()->unsigned()->notNull(),
            'languages_id' => $this->integer()->unsigned()->notNull(),
            'url' => $this->string()->notNull(),
            'seo_h1' => $this->string()->notNull(),
            'seo_h2' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'apply_url' => $this->string()->null(),
            'seo_title' => $this->string(),
            'seo_text' => $this->string(),
            'seo_desc' => $this->string(),
            'seo_keywords' => $this->string(),
        ], $tableOptions);

        $this->createIndex('idx_ct_url', 'categories_text', 'url');
        $this->createIndex('idx_ct_apply_url', 'categories_text', 'apply_url');
        $this->createIndex('idx_ct_name', 'categories_text', 'name');

        $this->createIndex('idx_ct_categories_id', 'categories_text', 'categories_id');
        $this->addForeignKey('fk_categories_text_categories', 'categories_text', 'categories_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_a_languages_id', 'categories_text', 'languages_id');
        $this->addForeignKey('fk_languages_for_language', 'categories_text', 'languages_id', 'languages', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_categories_text_categories', 'categories_text');
        $this->dropIndex('idx_ct_categories_id','categories_text');
        $this->dropForeignKey('fk_languages_for_language', 'categories_text');
        $this->dropIndex('idx_a_languages_id','categories_text');
        $this->dropTable('categories_text');
    }

}
