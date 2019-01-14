<?php

use yii\db\Migration;
use common\models\SitemapTasks;

class m181212_062335_sitemap_tables extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('sitemap_tasks', [
            'id' => $this->primaryKey()->unsigned(),
            'countries_id' => $this->integer(10)->unsigned()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
        ], $tableOptions);
        $this->addColumn('sitemap_tasks', 'status', 'SET(\''.SitemapTasks::PROCESSING_STATUS.'\',\''.SitemapTasks::PENDING_STATUS.'\',\''.SitemapTasks::FAILED_STATUS.'\', \''.SitemapTasks::FINISHED_STATUS.'\') NOT NULL DEFAULT \''.SitemapTasks::PENDING_STATUS.'\' AFTER `countries_id`');

        $this->createTable('sitemap_index', [
            'id' => $this->primaryKey()->unsigned(),
            'countries_id' => $this->integer(10)->unsigned()->notNull(),
            'tasks_id' => $this->integer()->unsigned()->notNull(),
            'link' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
        ], $tableOptions);


        $this->createIndex('idx_st_c_id', 'sitemap_tasks', 'countries_id');
        $this->addForeignKey('fk_st_countries', 'sitemap_tasks', 'countries_id', 'countries', 'id');

        $this->createIndex('idx_si_c_id', 'sitemap_index', 'countries_id');
        $this->addForeignKey('fk_si_countries', 'sitemap_index', 'countries_id', 'countries', 'id');
        $this->createIndex('idx_si_t_id', 'sitemap_index', 'tasks_id');
        $this->addForeignKey('fk_si_task', 'sitemap_index', 'tasks_id', 'sitemap_tasks', 'id');

    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_st_countries','sitemap_tasks');
        $this->dropIndex('idx_st_c_id','sitemap_tasks');

        $this->dropForeignKey('fk_si_countries','sitemap_index');
        $this->dropIndex('idx_si_c_id','sitemap_index');
        $this->dropForeignKey('fk_si_task','sitemap_index');
        $this->dropIndex('idx_si_t_id','sitemap_index');


        $this->dropTable('sitemap_index');
        $this->dropTable('sitemap_tasks');
    }

}
