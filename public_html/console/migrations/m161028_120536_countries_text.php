<?php

use yii\db\Migration;

class m161028_120536_countries_text extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('countries_text', [
            'id' => $this->primaryKey()->unsigned(),
            'countries_id' => $this->integer()->unsigned()->notNull(),
            'name' => $this->string()->notNull(),
            'name_rp' => $this->string(),
            'name_pp' => $this->string(),
        ], $tableOptions);

        $this->createIndex('idx_ct_countries_id', 'countries_text', 'countries_id');
        $this->addForeignKey('fk_countries_text_country', 'countries_text', 'countries_id', 'countries', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_countries_text_country','countries_text');
        $this->dropIndex('idx_ct_countries_id','countries_text');
        $this->dropTable('countries_text');
    }
}
