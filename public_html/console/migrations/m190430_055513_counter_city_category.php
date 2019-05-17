<?php

use yii\db\Migration;

class m190430_055513_counter_city_category extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {

            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('counter_city_category', [
            'id' => $this->primaryKey()->unsigned(),
            'categories_id' => $this->integer(11)->notNull()->unsigned(),
            'cities_id' => $this->integer(11)->notNull()->unsigned(),
            'ads_amount' => $this->integer(11)->notNull(),
        ], $tableOptions);

        $this->createIndex('idx_ccc_categories_id', 'counter_city_category', 'categories_id');
        $this->createIndex('idx_ccc_cities_id', 'counter_city_category', 'cities_id');
        $this->addForeignKey('fk_ccc_add_categories_id', 'counter_city_category', 'categories_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_ccc_cities_id', 'counter_city_category', 'cities_id', 'cities', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_ccc_cities_id', 'counter_city_category');
        $this->dropIndex('idx_ccc_cities_id','counter_city_category');
        $this->dropForeignKey('fk_ccc_add_categories_id', 'counter_city_category');
        $this->dropIndex('idx_ccc_categories_id','counter_city_category');
        $this->dropTable('counter_city_category');
    }
}
