<?php

use yii\db\Migration;

class m161028_113030_create_languages_text extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('languages_text', [
            'id' => $this->primaryKey()->unsigned(),
            'languages_id' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx_lt_languages_id', 'languages_text', 'languages_id');
        $this->addForeignKey('fk_languages_text_language', 'languages_text', 'languages_id', 'languages', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_languages_text_language','languages_text');
        $this->dropIndex('idx_lt_languages_id','languages_text');
        $this->dropTable('languages_text');
    }
}
