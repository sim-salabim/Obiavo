<?php

use yii\db\Migration;

class m161028_130029_categories_has_attributes extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('categories_has_attributes', [
            'categories_id' => $this->string(100)->notNull(),
            'attributes_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-cha-attributes_id','categories_has_attributes','attributes_id');
        $this->addForeignKey('fk-cha-attributes_id','categories_has_attributes','attributes_id','categories_attributes','id','CASCADE');
        $this->createIndex('idx-cha-categories_id','categories_has_attributes','categories_id');
        $this->addForeignKey('fk-cha-categories_id','categories_has_attributes','categories_id','categories','id','CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-cha-attributes_id','categories_has_attributes');
        $this->dropIndex('idx-cha-attributes_id','categories_has_attributes');
        $this->dropForeignKey('fk-cha-categories_id','categories_has_attributes');
        $this->dropIndex('idx-cha-categories_id','categories_has_attributes');
        $this->dropTable('categories_has_attributes');
    }
}
