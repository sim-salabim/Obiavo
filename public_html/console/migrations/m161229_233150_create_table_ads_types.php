<?php

use yii\db\Migration;

class m161229_233150_create_table_ads_types extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {

            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('ads_types', [
            'id' => $this->primaryKey()->unsigned(),
            'ads_id' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
        
        $this->addForeignKey('fk_ads_types', 'ads_types', 'ads_id', 'ads', 'id', 'CASCADE', 'CASCADE');
        
        $this->addCommentOnTable('ads_types','Типы объявлений (продать, купить, аренда и т.д)');
    }

    public function down()
    {
        $this->dropForeignKey('fk_ads_types', 'ads_types');
        $this->dropTable('ads_types');
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
