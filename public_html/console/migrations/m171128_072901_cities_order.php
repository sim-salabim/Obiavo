<?php

use yii\db\Migration;

class m171128_072901_cities_order extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('cities_order', [
            'id' => $this->primaryKey()->unsigned(),
            'cities_id' => $this->integer(10)->unsigned()->notNull(),
            'order' => $this->integer()->defaultValue(0)->notNull(),
        ], $tableOptions);

        $this->createIndex('idx_co_c_id', 'cities_order', 'cities_id');
        $this->addForeignKey('fk_co_cities', 'cities_order', 'cities_id', 'cities', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_co_cities','cities_order');
        $this->dropIndex('idx_co_c_id','cities_order');

        $this->dropTable('cities_order');
    }
}
