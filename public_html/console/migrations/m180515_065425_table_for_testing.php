<?php

use yii\db\Migration;

class m180515_065425_table_for_testing extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('test_tasks', [
            'id' => $this->primaryKey()->unsigned(),
            'ads_id' => $this->integer()->unsigned()->notNull(),
            'social_networks_groups_id' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addCommentOnTable('test_tasks', 'Таблица создана для тестирования функционала автопостинга');
        $this->createIndex('idx_tt_a_id', 'test_tasks', 'ads_id');
        $this->addForeignKey('at_ttadsid_ibfk_1', 'test_tasks', 'ads_id', 'ads', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_tt_sngi_id', 'test_tasks', 'social_networks_groups_id');
        $this->addForeignKey('at_ttsngid_ibfk_1', 'test_tasks', 'social_networks_groups_id', 'social_networks_groups', 'id', 'CASCADE', 'CASCADE');
    }


}

