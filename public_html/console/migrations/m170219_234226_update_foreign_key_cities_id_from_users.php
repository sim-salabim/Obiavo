<?php

use yii\db\Migration;

class m170219_234226_update_foreign_key_cities_id_from_users extends Migration
{
    public function up()
    {
        $this->addForeignKey('fk_users_cities', 'users', 'cities_id', 'cities', 'id', 'NO ACTION', 'CASCADE');
        $this->dropForeignKey('fk_users_city', 'users');
    }

    public function down()
    {
        $this->addForeignKey('fk_users_city', 'users', 'cities_id', 'cities', 'id', 'NO ACTION', 'CASCADE');
        $this->dropForeignKey('fk_users_cities', 'users');
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
