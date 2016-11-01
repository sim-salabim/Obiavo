<?php

use yii\db\Migration;

class m161028_125241_categories_generated extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('categories_generated', [
            'id' => $this->primaryKey()->unsigned(),
            'categories_id' => $this->integer()->unsigned()->notNull(),
            'countries_id' => $this->integer()->unsigned()->notNull(),
            'url' => $this->string()->notNull(),
            'techname' => $this->string()->notNull(),
            'seo_name' => $this->string()->notNull(),
            'seo_title' => $this->string(),
            'seo_desc' => $this->string(),
            'seo_keywords' => $this->string()->notNull(),
            'order' => $this->smallInteger(5)->notNull(),
        ], $tableOptions);

//        $this->createIndex('categories_id', 'categories_generated', 'categories_id');
        $this->addForeignKey('fk_categories_generated_category', 'categories_generated', 'categories_id', 'categories', 'id', 'CASCADE', 'CASCADE');

//        $this->createIndex('countries_id', 'categories_generated', 'countries_id');
        $this->addForeignKey('fk_categories_generated_country', 'categories_generated', 'countries_id', 'countries', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('categories_generated');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
