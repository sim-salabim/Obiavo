<?php

use yii\db\Migration;

class m181127_173355_categories_ads extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('categories_has_ads', [
            'id' => $this->primaryKey()->unsigned(),
            'categories_id' => $this->integer(10)->unsigned()->null(),
            'ads_id' => $this->integer(10)->unsigned()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx_cha_ads_id', 'categories_has_ads', 'ads_id');
        $this->addForeignKey('fk_cha_ads', 'categories_has_ads', 'ads_id', 'ads', 'id', 'CASCADE');
        $this->createIndex('idx_cha_cat_id', 'categories_has_ads', 'categories_id');
        $this->addForeignKey('fk_cha_cat_id', 'categories_has_ads', 'categories_id', 'categories', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_cha_ads','categories_has_ads');
        $this->dropIndex('idx_cha_ads_id','categories_has_ads');
        $this->dropForeignKey('fk_cha_cat_id','categories_has_ads');
        $this->dropIndex('idx_cha_cat_id','categories_has_ads');

        $this->dropTable('categories_has_ads');
    }
}
