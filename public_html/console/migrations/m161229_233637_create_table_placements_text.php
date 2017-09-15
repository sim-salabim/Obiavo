<?php

use yii\db\Migration;

class m161229_233637_create_table_placements_text extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {

            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('placements_text', [
            'id'            => $this->primaryKey()->unsigned(),
            'languages_id'  => $this->integer()->unsigned()->notNull(),
            'placements_id'  => $this->integer()->unsigned()->notNull(),
            'name'          => $this->string()->notNull(),
            'url'           => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx_pt_parent_id', 'placements_text', 'languages_id');
        $this->addForeignKey('fk_placements_id_text', 'placements_text', 'languages_id', 'languages', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_pt_placements_id', 'placements_text', 'placements_id');
        $this->addForeignKey('fk_placements_id_id', 'placements_text', 'placements_id', 'placements', 'id', 'CASCADE', 'CASCADE');
        
    }

    public function down()
    {
        $this->dropForeignKey('fk_placements_text', 'placements_text');
        $this->dropIndex('idx_pt_parent_id','placements_text');
        $this->dropForeignKey('fk_placements_id', 'placements_text');
        $this->dropIndex('idx_pt_placements_id','placements_text');
        $this->dropTable('placements_text');
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
