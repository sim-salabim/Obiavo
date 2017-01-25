<?php

use yii\db\Migration;

class m161229_233150_create_table_placements extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {

            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('placements', [
            'id' => $this->primaryKey()->unsigned(),            
        ], $tableOptions);                
        
        $this->addCommentOnTable('placements','Типы размещения объявлений (продать, купить, аренда и т.д)');
    }

    public function down()
    {        
        $this->dropTable('placements');
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
