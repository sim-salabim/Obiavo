<?php

use yii\db\Migration;

class m161028_134921_users extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('users', [
            'id' => $this->primaryKey()->unsigned(),
            'cities_id' => $this->integer()->unsigned()->notNull(),
            'email' => $this->string(100)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'patronymic' => $this->string()->notNull(),
            'sex' => "ENUM('0','1','2') NOT NULL DEFAULT '0'",
            'is_admin' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('cities_id', 'users', 'cities_id');
        $this->addForeignKey('fk_users_city', 'users', 'cities_id', 'cities', 'id', 'NO ACTION', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_users_city','users');
        $this->dropIndex('cities_id','users');
        $this->dropTable('users');
    }

}
