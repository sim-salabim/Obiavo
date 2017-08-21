<?php

use yii\db\Migration;

class m161028_131351_categories_attributes_text extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('categories_attributes_text', [
            'id' => $this->primaryKey()->unsigned(),
            'categories_attributes_id' => $this->integer()->unsigned()->notNull(),
            'languages_id' => $this->integer()->unsigned()->notNull(),
            'text' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx_cat_categories_attributes_id', 'categories_attributes_text', 'categories_attributes_id');
        $this->addForeignKey('fk_categories_attributes_text_category', 'categories_attributes_text', 'categories_attributes_id', 'categories_attributes', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('idx_cat_languages_id', 'categories_attributes_text', 'languages_id');
        $this->addForeignKey('fk_categories_attributes_text_language', 'categories_attributes_text', 'languages_id', 'languages', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_categories_attributes_text_category','categories_attributes_text');
        $this->dropIndex('idx_cat_categories_attributes_id','categories_attributes_text');
        $this->dropForeignKey('fk_categories_attributes_text_language','categories_attributes_text');
        $this->dropIndex('idx_cat_languages_id','categories_attributes_text');
        $this->dropTable('categories_attributes_text');
    }

}
