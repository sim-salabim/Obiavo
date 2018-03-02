<?php

use yii\db\Migration;

class m180302_053103_settings_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('settings', [
            'id' => $this->primaryKey()->unsigned(),
            'vk_token' => $this->string()->null()
            ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('settings');
    }

}
