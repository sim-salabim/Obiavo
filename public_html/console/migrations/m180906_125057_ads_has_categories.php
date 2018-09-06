<?php

use yii\db\Migration;

class m180906_125057_ads_has_categories extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('ads_has_categories', [
            'id' => $this->primaryKey()->unsigned(),
            'categories_id' => $this->integer(10)->unsigned()->null(),
            'ads_id' => $this->integer(10)->unsigned()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx_ahc_ads_id', 'ads_has_categories', 'ads_id');
        $this->addForeignKey('fk_ahc_ads', 'ads_has_categories', 'ads_id', 'ads', 'id', 'CASCADE');
        $this->createIndex('idx_ahc_cat_id', 'ads_has_categories', 'categories_id');
        $this->addForeignKey('fk_ahc_cat_id', 'ads_has_categories', 'categories_id', 'categories', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_ahc_ads','ads_has_categories');
        $this->dropIndex('idx_ahc_ads_id','ads_has_categories');
        $this->dropForeignKey('fk_ahc_cat_id','ads_has_categories');
        $this->dropIndex('idx_ahc_cat_id','ads_has_categories');

        $this->dropTable('ads_has_categories');
    }

}
