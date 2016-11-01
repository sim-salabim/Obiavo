<?php

use yii\db\Migration;

class m161028_112856_languages extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('languages', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'code' => $this->string()->unique()->notNull(),
            'active' => $this->boolean()->defaultValue(0)->notNull(),
            'is_default' => $this->boolean()->defaultValue(0)->notNull(),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('languages');
    }
}
