<?php

use yii\db\Migration;

class m170922_064151_files_tables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('files_exts_types', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->notNull(),
            'desc' => $this->string()->null(),
        ], $tableOptions);
        $this->createTable('files_exts', [
            'id' => $this->primaryKey()->unsigned(),
            'ext' => $this->string()->notNull(),
            'mime' => $this->string()->notNull(),
            'files_exts_types_id' => $this->integer(10)->unsigned()->notNull(),
        ], $tableOptions);

        $this->createTable('files', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->notNull(),
            'hash' => $this->string()->notNull(),
            'files_exts_id' => $this->integer(10)->unsigned()->notNull(),
            'users_id' => $this->integer(10)->unsigned()->null()->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('idx_fet_id', 'files_exts', 'files_exts_types_id');
        $this->addForeignKey('files_exts_ibfk_1', 'files_exts', 'files_exts_types_id', 'files_exts_types', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('idx_fe_id', 'files', 'files_exts_id');
        $this->addForeignKey('fk_files_exts_id', 'files', 'files_exts_id', 'files_exts', 'id', 'CASCADE', 'NO ACTION');

        $this->createIndex('idx_users_id', 'files', 'users_id');
        $this->addForeignKey('fk_f_users', 'files', 'users_id', 'users', 'id', 'CASCADE', 'NO ACTION');

        $this->createTable('ads_has_files', [
            'files_id' => $this->integer(10)->unsigned()->notNull(),
            'ads_id' => $this->integer(10)->unsigned()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx_ahf_files_id', 'ads_has_files', 'files_id');
        $this->addForeignKey('fk_ahf_files_id', 'ads_has_files', 'files_id', 'files', 'id', 'CASCADE', 'CASCADE');

        $this->createIndex('idx_ahf_ads_id', 'ads_has_files', 'ads_id');
        $this->addForeignKey('fk_ahf_ads_id', 'ads_has_files', 'ads_id', 'ads', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropForeignKey('fk_ahf_files_id','ads_has_files');
        $this->dropIndex('idx_ahf_files_id','ads_has_files');
        $this->dropForeignKey('fk_ahf_ads_id','ads_has_files');
        $this->dropIndex('idx_ahf_ads_id','ads_has_files');
        $this->dropTable('ads_has_files');

        $this->dropForeignKey('fk_files_exts_id','files');
        $this->dropIndex('idx_fe_id','files');
        $this->dropForeignKey('fk_f_users','files');
        $this->dropIndex('idx_users_id','files');

        $this->dropTable('files');

        $this->dropForeignKey('files_exts_ibfk_1','files_exts');
        $this->dropIndex('idx_fet_id','files_exts');
        $this->dropTable('files_exts');

        $this->dropTable('files_exts_types');
    }
}
