<?php

use yii\db\Migration;

class m161028_130028_attributes_units extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('attributes_units', [
            'id' => $this->primaryKey()->unsigned(),
            'value' => $this->string(100)->notNull(),
            'attributes_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('attributes_units_text', [
            'id' => $this->primaryKey()->unsigned(),
            'attributes_units_id' => $this->unsigned(),
            'languages_id' => $this->unsigned(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-au-attributes_id','attributes_units','attributes_id');
        $this->addForeignKey('fk-au-attributes_id','attributes_units','attributes_id','attributes_units','id','CASCADE');
        $this->createIndex('idx-aut-attributes_units_id','attributes_units_text','attributes_units_id');
        $this->addForeignKey('fk-aut-attributes_units_id','attributes_units_text','attributes_units_id','attributes_units','id','CASCADE');
        $this->createIndex('idx-aut-languages_id','attributes_units_text','languages_id');
        $this->addForeignKey('fk-aut-languages_id','attributes_units_text','languages_id','languages_id','id','CASCADE');

    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-au-attributes_id','attributes_units');
        $this->dropIndex('idx-au-attributes_id','attributes_units');
        $this->dropForeignKey('fk-aut-attributes_units_id','attributes_units_text');
        $this->dropIndex('idx-aut-attributes_units_id','attributes_units_text');
        $this->dropForeignKey('fk-aut-languages_id','attributes_units_text');
        $this->dropIndex('idx-aut-languages_id','attributes_units_text');
        $this->dropTable('attributes_units');
        $this->dropTable('attributes_units_text');
    }
}
