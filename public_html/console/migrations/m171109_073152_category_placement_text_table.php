<?php

use yii\db\Migration;

class m171109_073152_category_placement_text_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {

            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('categories_placement_text', [
            'id' => $this->primaryKey()->unsigned(),
            'category_placement_id' => $this->integer()->unsigned()->notNull(),
            'languages_id' => $this->integer()->unsigned()->notNull(),
            'seo_title' => $this->string(),
            'seo_h1' => $this->string(),
            'seo_h2' => $this->string(),
            'name' => $this->string(),
            'seo_text' => $this->text(),
            'seo_desc' => $this->text(),
            'seo_keywords' => $this->string(),
        ], $tableOptions);

        $this->createIndex('idx_cpt_category_placement_id', 'categories_placement_text', 'category_placement_id');
        $this->addForeignKey('fk_cpt_categories_placement_text', 'categories_placement_text', 'category_placement_id', 'categories_has_placements', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_cpt_languages_id', 'categories_placement_text', 'languages_id');
        $this->addForeignKey('fk_cpt_languages_for_language', 'categories_placement_text', 'languages_id', 'languages', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_cpt_categories_placement_text', 'categories_placement_text');
        $this->dropIndex('idx_cpt_category_placement_id','categories_placement_text');
        $this->dropForeignKey('fk_cpt_languages_for_language', 'categories_placement_text');
        $this->dropIndex('idx_cpt_languages_id','categories_placement_text');
        $this->dropTable('categories_placement_text');
    }

}
