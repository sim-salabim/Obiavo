<?php

use yii\db\Migration;

class m161028_130026_categories_attributes extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('categories_attributes', [
            'id' => $this->primaryKey()->unsigned(),
            'categories_id' => $this->integer()->unsigned()->notNull(),
            'attributes_types_id' => $this->integer()->unsigned()->notNull(),
            'techname' => $this->string()->notNull(),
            'active' => $this->boolean()->defaultValue(1),
        ], $tableOptions);


//        $this->createIndex('categories_id', 'categories_attributes', 'categories_id');
        $this->addForeignKey('fk_categories_attributes_category', 'categories_attributes', 'categories_id', 'categories', 'id', 'CASCADE', 'CASCADE');

//        $this->createIndex('attributes_types_id', 'categories_attributes', 'attributes_types_id');
        $this->addForeignKey('fk_categories_attributes_attribute_type', 'categories_attributes', 'attributes_types_id', 'attributes_types', 'id', 'CASCADE', 'CASCADE');

        $this->addCommentOnTable('categories_attributes', 'Таблица параметров какой-то из категорий при подаче объявления ');
    }

    public function down()
    {
        $this->dropTable('categories_attributes');
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
