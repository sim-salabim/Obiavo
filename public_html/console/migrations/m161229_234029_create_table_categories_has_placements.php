<?php

use yii\db\Migration;

class m161229_234029_create_table_categories_has_placements extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {

            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('categories_has_placements', [
            'id'            => $this->primaryKey()->unsigned(),
            'categories_id'  => $this->integer()->unsigned()->notNull(),
            'placements_id'  => $this->integer()->unsigned()->notNull(),            
        ], $tableOptions);
        
        $this->addForeignKey('fk_categories_has_placements_categories_id', 'categories_has_placements', 'categories_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_categories_has_placements_id', 'categories_has_placements', 'placements_id', 'placements', 'id', 'CASCADE', 'CASCADE');
        
        $this->addCommentOnTable('categories_has_placements','Оношение типов объявлений (купить, продать, аренда и тд) к категориям');
    }

    public function down()
    {
        $this->dropForeignKey('fk_categories_has_placements_categories_id', 'categories_has_placements');
        $this->dropForeignKey('fk_categories_has_placements_id', 'categories_has_placements');
        $this->dropTable('categories_has_placements');
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
