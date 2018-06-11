<?php

use yii\db\Migration;

class m180531_053100_categories_raw_text_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('categories_text_raw', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->null(),
            'name_ip' => $this->string()->null(),
            'name_rp' => $this->string()->null(),
            'name_dp' => $this->string()->null(),
            'name_vp' => $this->string()->null(),
            'name_tp' => $this->string()->null(),
            'name_pp' => $this->string()->null(),
        ], $tableOptions);
    }

    public function safeDown()
    {
        echo "m180531_053100_categories_raw_text_table cannot be reverted.\n";

        return false;
    }

}
