<?php

use yii\db\Migration;

class m171221_062802_social_networks extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('social_networks', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->notNull(),
            'active' => $this->boolean()->defaultValue(false),
            'autoposting' => $this->boolean()->defaultValue(false),
        ], $tableOptions);

    }

    public function safeDown()
    {
        $this->dropTable('cities_order');
    }
}
