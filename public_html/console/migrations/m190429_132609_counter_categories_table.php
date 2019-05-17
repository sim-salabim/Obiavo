<?php

use yii\db\Migration;

class m190429_132609_counter_categories_table extends Migration
{
    public function up()
{
    $tableOptions = null;
    if ($this->db->driverName === 'mysql') {

        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    }

    $this->createTable('counter_category', [
        'id' => $this->primaryKey()->unsigned(),
        'categories_id' => $this->integer(11)->notNull()->unsigned(),
        'countries_id' => $this->integer(11)->notNull()->unsigned(),
        'ads_amount' => $this->integer(11)->notNull(),
    ], $tableOptions);

    $this->createIndex('idx_cc_categories_id', 'counter_category', 'categories_id');
    $this->createIndex('idx_cc_countries_id', 'counter_category', 'countries_id');
    $this->addForeignKey('fk_cc_add_categories_id', 'counter_category', 'categories_id', 'categories', 'id', 'CASCADE', 'CASCADE');
    $this->addForeignKey('fk_cc_countries_id', 'counter_category', 'countries_id', 'countries', 'id', 'CASCADE', 'CASCADE');
}

    public function down()
    {
        $this->dropForeignKey('fk_cc_countries_id', 'counter_category');
        $this->dropIndex('idx_cc_countries_id','counter_category');
        $this->dropForeignKey('fk_cc_add_categories_id', 'counter_category');
        $this->dropIndex('idx_cc_categories_id','counter_category');
        $this->dropTable('counter_category');
    }
}
