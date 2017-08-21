<?php

use yii\db\Migration;

class m161028_130027_attributes_predefined_values extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('attributes_predefined_values', [
            'attributes_id' => $this->unsigned(),
            'value' => $this->string(100)->notNull(),
            'order' => $this->integer()->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('idx-apv-attributes_id','attributes_predefined_values','attributes_id');
        $this->addForeignKey('fk-apv-attributes_id','attributes_predefined_values','attributes_id','categories_attributes','id','CASCADE');
        $this->createIndex('idx-ca-attributes_predefined_value','categories_attributes','predefined_value_id');
        $this->addForeignKey('fk_categories_attributes_predefined_value', 'categories_attributes', 'predefined_value_id', 'attributes_predefined_values', 'id', 'CASCADE', 'CASCADE');

    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_categories_attributes_predefined_value', 'categories_attributes');
        $this->dropIndex('idx-ca-attributes_predefined_value','categories_attributes');
        $this->dropForeignKey('fk-apv-attributes_id','attributes_predefined_values');
        $this->dropIndex('idx-apv-attributes_id','attributes_predefined_values');
        $this->dropTable('attributes_predefined_values');
    }
}
