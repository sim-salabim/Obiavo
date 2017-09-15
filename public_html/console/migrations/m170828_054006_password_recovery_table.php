<?php

use yii\db\Migration;

class m170828_054006_password_recovery_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('password_recovery', [
            'id' => $this->primaryKey()->unsigned(),
            'users_id' => $this->integer()->unsigned()->notNull(),
            'recovered' => $this->boolean()->defaultValue(0)->notNull(),
            'hash' => $this->string(255)->notNull(),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
        ], $tableOptions);

        $this->createIndex('idx_pr_users_id', 'password_recovery', 'users_id');
        $this->addForeignKey('fk_password_recovery_user', 'password_recovery', 'users_id', 'users', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropForeignKey('fk_password_recovery_user','password_recovery');
        $this->dropIndex('idx_pr_users_id','password_recovery');
        $this->dropTable('password_recovery');
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
