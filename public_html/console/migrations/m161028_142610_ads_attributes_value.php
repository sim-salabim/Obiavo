<?php

use yii\db\Migration;

class m161028_142610_ads_attributes_value extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('ads_attributes_value', [
            'id' => $this->primaryKey()->unsigned(),
            'ads_id' => $this->integer()->unsigned()->notNull(),
            'categories_attributes_id' => $this->integer()->unsigned()->notNull(),
            'value' => $this->text(),
        ], $tableOptions);

        $this->createIndex('idx_ads_id', 'ads_attributes_value', 'ads_id');
        $this->addForeignKey('fk_ads_attributes_value_ads', 'ads_attributes_value', 'ads_id', 'ads', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('idx_categories_attributes_id', 'ads_attributes_value', 'categories_attributes_id');
        $this->addForeignKey('fk_ads_attributes_value_category', 'ads_attributes_value', 'categories_attributes_id', 'categories_attributes', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_ads_attributes_value_ads','ads_attributes_value');
        $this->dropIndex('idx_ads_id','ads_attributes_value');
        $this->dropForeignKey('fk_ads_attributes_value_category','ads_attributes_value');
        $this->dropIndex('idx_categories_attributes_id','ads_attributes_value');
        $this->dropTable('ads_attributes_value');
    }

}
