<?php

use yii\db\Migration;

class m171221_063157_social_network_groups_main extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('social_networks_groups_main', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);
        $this->addColumn('categories', 'social_networks_groups_main_id', $this->integer()->unsigned());
        $this->createIndex('idx_sngm_id', 'categories', 'social_networks_groups_main_id');
        $this->addForeignKey('sngm_ibfk_1', 'categories', 'social_networks_groups_main_id', 'social_networks_groups_main', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('sngm_ibfk_1','categories');
        $this->dropIndex('idx_sngm_id','categories');
        $this->dropTable('social_networks_groups_main');
    }
}
