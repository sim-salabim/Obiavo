<?php

use yii\db\Migration;

class m161028_133012_cities extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('cities', [
            'id' => $this->primaryKey()->unsigned(),
            'regions_id' => $this->integer()->unsigned()->notNull(),
            'domain' => $this->string()->notNull(),
            'active' => $this->boolean()->defaultValue(1),
            'sitemap' => $this->boolean()->defaultValue(0),
            'meta_google' => $this->string(),
            'meta_yandex' => $this->string(),
            'longitude' => $this->string(100),
            'latitude' => $this->string(100),
            'ads_amount' => $this->integer(11)->defaultValue(null)
        ], $tableOptions);

        $this->createIndex('idx_c_regions_id', 'cities', 'regions_id');
        $this->createIndex('idx_c_domain', 'cities', 'domain');
        $this->addForeignKey('fk_cities_region', 'cities', 'regions_id', 'regions', 'id', 'CASCADE', 'CASCADE');

        $this->addcommentOnColumn('cities','longitude','Долгота');

        $this->addcommentOnColumn('cities','latitude','Широта');
    }

    public function down()
    {
        $this->dropForeignKey('fk_cities_region','cities');
        $this->dropIndex('idx_c_regions_id','cities');
        $this->dropTable('cities');
    }
}
