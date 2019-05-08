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
            'price' => $this->bigInteger(),
            'session_token' => $this->string()->null()->defaultValue(null),
            'only_locally' => 'TINYINT(1) NOT NULL DEFAULT 0',
            'active' => $this->boolean()->defaultValue(true),
            'categories_list' => $this->text()->null()->defaultValue(null),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx_a_created_at', 'ads', 'created_at');
        $this->createIndex('idx_a_title', 'ads', 'title');
        $this->createIndex('idx_a_categories_list', 'ads', 'categories_list');
        $this->createIndex('idx_a_price', 'ads', 'price');
        $this->createIndex('idx_a_active', 'ads', 'active');
        $this->createIndex('idx_a_cities_id', 'ads', 'cities_id');
        $this->addForeignKey('fk_ads_city', 'ads', 'cities_id', 'cities', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('idx_a_users_id', 'ads', 'users_id');
        $this->addForeignKey('fk_ads_user', 'ads', 'users_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('idx_a_categories_id', 'ads', 'categories_id');
        $this->addForeignKey('fk_ads_category', 'ads', 'categories_id', 'categories', 'id', 'CASCADE', 'CASCADE');

        $this->addCommentOnTable('ads', 'Таблица объявлений');
    }

    public function down()
    {
        $this->dropForeignKey('fk_ads_city','ads');
        $this->dropIndex('idx_a_cities_id','ads');
        $this->dropIndex('idx_a_categories_list','ads');
        $this->dropForeignKey('fk_ads_user','ads');
        $this->dropIndex('idx_a_users_id','ads');
        $this->dropForeignKey('fk_ads_category','ads');
        $this->dropIndex('idx_a_categories_id','ads');
        $this->dropTable('ads');
    }

}
