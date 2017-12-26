<?php

use yii\db\Migration;

class m171221_070915_social_network_groups extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('social_networks_groups', [
            'id' => $this->primaryKey()->unsigned(),
            'social_networks_groups_main_id' => $this->integer()->unsigned()->notNull(),
            'social_networks_id' => $this->integer()->unsigned()->notNull(),
            'cities_id' => $this->integer()->unsigned()->null(),
            'regions_id' => $this->integer()->unsigned()->null(),
            'name' => $this->string()->notNull(),
            'code_sm' => $this->text(),
            'code_md' => $this->text(),
            'code_lg' => $this->text(),
        ], $tableOptions);
        $this->addColumn('social_networks', 'default_group_id', $this->integer()->unsigned()->null());
        $this->addColumn('social_networks', 'order', $this->integer()->defaultValue(0));
        $this->createIndex('idx_dgid_id', 'social_networks', 'default_group_id');
        $this->addForeignKey('dgid_ibfk_1', 'social_networks', 'default_group_id', 'social_networks_groups', 'id', 'SET NULL', 'SET NULL');
        $this->createIndex('idx_sngmid_id', 'social_networks_groups', 'social_networks_groups_main_id');
        $this->addForeignKey('sngmid_ibfk_1', 'social_networks_groups', 'social_networks_groups_main_id', 'social_networks_groups_main', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_sn_id', 'social_networks_groups', 'social_networks_id');
        $this->addForeignKey('sn_id_ibfk_1', 'social_networks_groups', 'social_networks_id', 'social_networks', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_snci_id', 'social_networks_groups', 'cities_id');
        $this->addForeignKey('snci_id_ibfk_1', 'social_networks_groups', 'cities_id', 'cities', 'id', 'SET NULL', 'SET NULL');
        $this->createIndex('idx_snri_id', 'social_networks_groups', 'regions_id');
        $this->addForeignKey('snri_id_ibfk_1', 'social_networks_groups', 'regions_id', 'regions', 'id', 'SET NULL', 'SET NULL');
    }

    public function safeDown()
    {
        $this->dropForeignKey('dgid_ibfk_1','social_networks');
        $this->dropIndex('idx_dgid_id','social_networks');
        $this->dropColumn('social_networks', 'default_group_id');
        $this->dropForeignKey('sngmid_ibfk_1','social_networks_groups');
        $this->dropIndex('idx_sngmid_id','social_networks_groups');
        $this->dropForeignKey('sn_id_ibfk_1','social_networks_groups');
        $this->dropIndex('idx_sn_id','social_networks_groups');
        $this->dropForeignKey('snci_id_ibfk_1','social_networks_groups');
        $this->dropIndex('idx_snci_id','social_networks_groups');
        $this->dropForeignKey('snri_id_ibfk_1','social_networks_groups');
        $this->dropIndex('idx_snri_id','social_networks_groups');
        $this->dropTable('social_networks_groups');
    }
}
