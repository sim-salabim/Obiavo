<?php

use yii\db\Migration;

class m161028_134408_cities_text extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('cities_text', [
            'id' => $this->primaryKey()->unsigned(),
            'cities_id' => $this->integer()->unsigned()->notNull(),
            'languages_id' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string()->notNull(),
            'name_rp' => $this->string(),
            'name_pp' => $this->string(),
        ], $tableOptions);

//        $this->createIndex('cities_id', 'cities_text', 'cities_id');
        $this->addForeignKey('fk_cities_text_city', 'cities_text', 'cities_id', 'cities', 'id', 'CASCADE', 'CASCADE');

//        $this->createIndex('languages_id', 'cities_text', 'languages_id');
        $this->addForeignKey('fk_cities_text_language', 'cities_text', 'languages_id', 'languages', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('cities_text');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
