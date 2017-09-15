<?php

use yii\db\Migration;

class m161028_122106_regions_text extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('regions_text', [
            'id' => $this->primaryKey()->unsigned(),
            'regions_id' => $this->integer()->unsigned()->notNull(),
            'languages_id' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string()->notNull(),
            'name_rp' => $this->string()->null(),
            'name_pp' => $this->string()->null(),
        ], $tableOptions);

        $this->createIndex('idx_rt_countries_id', 'regions_text', 'countries_id');
        $this->addForeignKey('fk_regions_text_region', 'regions_text', 'regions_id', 'regions', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('idx_rt_languages_id', 'regions_text', 'languages_id');
        $this->addForeignKey('fk_regions_t_lang', 'regions_text', 'languages_id', 'languages', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_regions_t_lang', 'regions_text');
        $this->dropIndex('idx_rt_countries_id','regions_text');
        $this->dropForeignKey('fk_regions_t_country', 'regions_text');
        $this->dropIndex('idx_rt_languages_id','regions_text');
        $this->dropTable('regions_text');
    }
}
