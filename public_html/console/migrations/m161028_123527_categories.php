<?php

use yii\db\Migration;

class m161028_123527_categories extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('categories', [
            'id' => $this->primaryKey()->unsigned(),
            'parent_id' => $this->integer()->unsigned()->null(),
            'techname' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'active' => $this->boolean()->defaultValue(1),
            'brand' => $this->boolean()->defaultValue(0),
            'excel_id' => $this->integer()->null(),
            'seo_id' => $this->integer()->null(),
            'clean_harakterisitka' => $this->integer()->null(),
            'href' => $this->boolean()->defaultValue(0),
            'href_id' => $this->integer()->null(),
            'order' => $this->integer()->defaultValue(0),
        ], $tableOptions);

        $this->createIndex('idx_c_parent_id', 'categories', 'parent_id');
        $this->addForeignKey('fk_categories_category', 'categories', 'parent_id', 'categories', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_c_categories_list', 'categories', 'categories_list');
    }

    public function down()
    {
        $this->dropForeignKey('fk_categories_category','categories');
        $this->dropIndex('idx_c_parent_id','categories');
        $this->dropIndex('idx_c_categories_list','categories');
        $this->dropTable('categories');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
