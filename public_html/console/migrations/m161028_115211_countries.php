<?php

use yii\db\Migration;
use yii\db\Schema;

class m161028_115211_countries extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('countries', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'languages_id' => $this->integer(10)->unsigned()->notNull(),
            'domain' => $this->string()->notNull(),
            'active' => $this->boolean()->defaultValue(1)->notNull(),
            'meta_google' => $this->string(),
            'meta_yandex' => $this->string(),
            'longitude' => $this->string(100),
            'latitude' => $this->string(100)
        ], $tableOptions);

//        $this->createIndex('languages_id', 'countries', 'languages_id');
        $this->addForeignKey('fk_countries_language', 'countries', 'languages_id', 'languages', 'id', 'CASCADE', 'CASCADE');

        $this->addCommentOnColumn('countries','longitude','Долгота');

        $this->addCommentOnColumn('countries','latitude','Широта');
    }

    public function safeDown()
    {
        $this->dropTable('countries');
    }
}
