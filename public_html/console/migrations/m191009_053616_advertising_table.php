<?php

use yii\db\Migration;

class m191009_053616_advertising_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {

            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('advertising', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'placement' => $this->integer(2)->notNull(),
            'code_en' => $this->text()->notNull(),
            'code_ru' => $this->text()->notNull(),
            'active' => $this->boolean()->defaultValue(true),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('advertising');
    }

}
