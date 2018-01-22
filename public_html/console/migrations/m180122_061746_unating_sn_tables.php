<?php

use yii\db\Migration;

class m180122_061746_unating_sn_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('social_networks_groups_categories', [
            'categories_id' => $this->integer(10)->unsigned()->notNull(),
            'group_id' => $this->integer(10)->unsigned()->notNull(),
        ], $tableOptions);
        $this->addCommentOnTable('social_networks_groups_categories', 'Таблица для свазывания сообществ соцсетей и категорий обьявлений');
        $this->createIndex('idx_sngc_c_id', 'social_networks_groups_categories', 'categories_id');
        $this->addForeignKey('sngc_c_ibfk_1', 'social_networks_groups_categories', 'categories_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_sngc_g_id', 'social_networks_groups_categories', 'group_id');
        $this->addForeignKey('sngc_g_ibfk_1', 'social_networks_groups_categories', 'group_id', 'social_networks_groups', 'id', 'CASCADE', 'CASCADE');
        $this->addCommentOnColumn('social_networks_groups_categories','categories_id','ID категории');
        $this->addCommentOnColumn('social_networks_groups_categories','group_id','ID группы social_networks_groups.id');

        $this->createTable('social_networks_groups_main_groups', [
            'group_id' => $this->integer(10)->unsigned()->notNull(),
            'main_group_id' => $this->integer(10)->unsigned()->notNull(),
        ], $tableOptions);
        $this->addCommentOnTable('social_networks_groups_main_groups', 'Таблица для свазывания обьеденений сообществ (social_networks_groups_main) и самих сообществ. Используется для установки дефолтных сообществ обьеденений групп');
        $this->addCommentOnColumn('social_networks_groups_main_groups','group_id','ID группы social_networks_groups.id');
        $this->addCommentOnColumn('social_networks_groups_main_groups','main_group_id','ID обьеденения групп social_networks_groups_main.id');
        $this->createIndex('idx_sngmg_g_id', 'social_networks_groups_main_groups', 'group_id');
        $this->addForeignKey('sngmg_g_ibfk_1', 'social_networks_groups_main_groups', 'group_id', 'social_networks_groups', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_sngmg_mg_id', 'social_networks_groups_main_groups', 'main_group_id');
        $this->addForeignKey('sngmg_mg_ibfk_1', 'social_networks_groups_main_groups', 'main_group_id', 'social_networks_groups_main', 'id', 'CASCADE', 'CASCADE');

    }

    public function safeDown()
    {
        $this->dropForeignKey('sngc_c_ibfk_1','social_networks_groups_categories');
        $this->dropIndex('idx_sngc_c_id','social_networks_groups_categories');
        $this->dropForeignKey('sngc_g_ibfk_1','social_networks_groups_categories');
        $this->dropIndex('idx_sngc_g_id','social_networks_groups_categories');
        $this->dropTable('social_networks_groups_categories');
    }
}
