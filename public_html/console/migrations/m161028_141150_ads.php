<?php

use yii\db\Migration;

class m161028_141150_ads extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('ads', [
            'id' => $this->primaryKey()->unsigned(),
            'cities_id' => $this->integer()->unsigned()->notNull(),
            'users_id' => $this->integer()->unsigned()->notNull(),
            'categories_id' => $this->integer()->unsigned()->notNull(),
            'title' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
            'price' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

//        $this->createIndex('cities_id', 'ads', 'cities_id');
        $this->addForeignKey('fk_ads_city', 'ads', 'cities_id', 'cities', 'id', 'CASCADE', 'CASCADE');

//        $this->createIndex('users_id', 'ads', 'users_id');
        $this->addForeignKey('fk_ads_user', 'ads', 'users_id', 'users', 'id', 'CASCADE', 'CASCADE');

//        $this->createIndex('categories_id', 'ads', 'categories_id');
        $this->addForeignKey('fk_ads_category', 'ads', 'categories_id', 'categories', 'id', 'CASCADE', 'CASCADE');

        $this->addCommentOnTable('ads', 'Таблица объявлений');
    }

    public function down()
    {
        $this->dropTable('ads');
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
