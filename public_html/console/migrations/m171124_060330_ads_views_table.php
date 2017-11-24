<?php

use yii\db\Migration;

class m171124_060330_ads_views_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('ads_views', [
            'id' => $this->primaryKey()->unsigned(),
            'users_id' => $this->integer(10)->unsigned()->null(),// null потому что если посмотрел не зарегистрированный юзер
            'ads_id' => $this->integer(10)->unsigned()->notNull(),
            'create_at' => $this->timestamp()->notNull(),
            'update_at' => $this->timestamp()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx_av_u_id', 'ads_views', 'users_id');
        $this->addForeignKey('fk_av_users', 'ads_views', 'users_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->createIndex('idx_av_ads_id', 'ads_views', 'ads_id');
        $this->addForeignKey('fk_av_ads_id', 'ads_views', 'ads_id', 'ads', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropForeignKey('users_av_users','ads_views');
        $this->dropIndex('users_av_users','ads_views');

        $this->dropTable('ads_views');
    }
}
