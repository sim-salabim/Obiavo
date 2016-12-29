<?php

use yii\db\Migration;

class m161229_233637_create_table_ads_types_text extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {

            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('ads_types_text', [
            'id'            => $this->primaryKey()->unsigned(),
            'languages_id'  => $this->integer()->unsigned()->notNull(),
            'ads_types_id'  => $this->integer()->unsigned()->notNull(),
            'name'          => $this->string()->notNull(),
        ], $tableOptions);
        
        $this->addForeignKey('fk_ads_types_text', 'ads_types_text', 'languages_id', 'languages', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_ads_types_id', 'ads_types_text', 'ads_types_id', 'ads_types', 'id', 'CASCADE', 'CASCADE');
        
    }

    public function down()
    {
        $this->dropForeignKey('fk_ads_types_text', 'ads_types_text');
        $this->dropForeignKey('fk_ads_types_id', 'ads_types_text');
        $this->dropTable('ads_types_text');
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
