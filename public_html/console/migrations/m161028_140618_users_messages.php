<?php

use yii\db\Migration;

class m161028_140618_users_messages extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('users_messages', [
            'id' => $this->primaryKey()->unsigned(),
            'from_users_id' => $this->integer()->unsigned()->notNull(),
            'to_users_id' => $this->integer()->unsigned()->notNull(),
            'text' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

//        $this->createIndex('from_users_id', 'users_messages', 'from_users_id');
        $this->addForeignKey('fk_users_from_user', 'users_messages', 'from_users_id', 'users', 'id', 'CASCADE', 'CASCADE');

//        $this->createIndex('to_users_id', 'users_messages', 'to_users_id');
        $this->addForeignKey('fk_users_to_user', 'users_messages', 'to_users_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->addCommentOnTable('users_messages', 'Таблица личных сообщений среди пользователей');

        $this->addCommentOnColumn('users_messages','from_users_id','От кого соообщение');
        $this->addCommentOnColumn('users_messages','to_users_id','Адресат сообщения');
    }

    public function down()
    {
        $this->dropTable('users_messages');
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
