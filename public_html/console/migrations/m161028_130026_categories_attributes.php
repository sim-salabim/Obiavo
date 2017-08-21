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
            'techname' => $this->string()->notNull(),
            'attributes_types_id' => $this->integer()->unsigned()->notNull(),
            'predefined_value_id' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->createIndex('idx-ca-attributes_types','categories_attributes','attributes_types_id');
        $this->addForeignKey('fk_categories_attributes_attribute_type', 'categories_attributes', 'attributes_types_id', 'attributes_types', 'id', 'CASCADE', 'CASCADE');

        $this->addCommentOnTable('categories_attributes', 'Таблица параметров какой-то из категорий при подаче объявления ');
    }

    public function down()
    {
        $this->dropForeignKey('fk_categories_attributes_attribute_type', 'categories_attributes');
        $this->dropIndex('idx-ca-attributes_types','categories_attributes');

        $this->dropTable('categories_attributes');
    }
}
