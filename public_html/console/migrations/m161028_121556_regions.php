<?php

use yii\db\Migration;

class m161028_121556_regions extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('regions', [
            'id' => $this->primaryKey()->unsigned(),
            'countries_id' => $this->integer()->unsigned()->notNull(),
            'active' => $this->boolean()->notNull()->defaultValue(1),
            'domain' => $this->string(),
            'meta_google' =>  $this->string()->null(),
            'meta_yandex' => $this->string()->null(),
            'longitude' => $this->string(100)->null(),
            'latitude' => $this->string(100)->null()
        ], $tableOptions);

//        $this->createIndex('countries_id', 'regions', 'countries_id');
        $this->addForeignKey('fk_regions_country', 'regions', 'countries_id', 'countries', 'id', 'CASCADE', 'CASCADE');

        $this->addcommentOnColumn('regions','longitude','Долгота');

        $this->addcommentOnColumn('regions','latitude','Широта');
    }

    public function safeDown()
    {
        $this->dropTable('regions');
    }
}
