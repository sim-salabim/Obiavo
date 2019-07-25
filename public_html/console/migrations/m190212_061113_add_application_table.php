<?php

use yii\db\Migration;

class m190212_061113_add_application_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {

            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('add_application', [
            'id' => $this->primaryKey()->unsigned(),
            'active' => $this->boolean()->defaultValue(1),
            'category_default' => $this->boolean()->defaultValue(0),
            'placements_default' => $this->boolean()->defaultValue(0),
        ], $tableOptions);

        $this->createTable('add_application_text', [
            'id' => $this->primaryKey()->unsigned(),
            'add_application_id' => $this->integer()->unsigned()->notNull(),
            'languages_id' => $this->integer()->unsigned()->notNull(),
            'url' => $this->string()->notNull(),
            'seo_h1' => $this->string()->notNull(),
            'seo_h2' => $this->string()->notNull(),
            'seo_title' => $this->string(),
            'seo_text' => $this->text(),
            'seo_text1' => $this->text(),
            'seo_text2' => $this->text(),
            'seo_text3' => $this->text(),
            'seo_text4' => $this->text(),
            'seo_text5' => $this->text(),
            'seo_text6' => $this->text(),
            'seo_text7' => $this->text(),
            'seo_desc' => $this->text(),
            'seo_keywords' => $this->string(),
            'category_default' => $this->boolean()->defaultValue(false),
            'placements_default' => $this->boolean()->defaultValue(false),
        ], $tableOptions);

        $this->createIndex('idx_aat_category_default', 'add_application_text', 'category_default');
        $this->createIndex('idx_aat_placements_default', 'add_application_text', 'placements_default');
        $this->createIndex('idx_aat_add_application_id', 'add_application_text', 'add_application_id');
        $this->addForeignKey('fk_aat_add_application_id', 'add_application_text', 'add_application_id', 'add_application', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_aat_languages_id', 'add_application_text', 'languages_id');
        $this->addForeignKey('fk_aat_for_language', 'add_application_text', 'languages_id', 'languages', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_aat_add_application_id', 'add_application_text');
        $this->dropIndex('idx_aat_add_application_id','add_application_text');
        $this->dropForeignKey('fk_aat_for_language', 'add_application_text');
        $this->dropIndex('idx_aat_languages_id','add_application_text');
        $this->dropTable('add_application');
    }

}
