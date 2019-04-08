<?php

use yii\db\Migration;

class m170920_072725_alter_users_and_ads_table extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'phone_number', $this->string()->null());
        $this->addColumn('ads', 'expiry_date', $this->bigInteger()->notNull());
        $this->addColumn('ads', 'url', $this->string()->notNull()->unique());
        $this->addColumn('ads', 'placements_id', $this->integer(10)->unsigned()->notNull());

        $this->createIndex('idx_a_expiry_date', 'ads', 'expiry_date');
        $this->createIndex('idx_a_url', 'ads', 'url');
        $this->createIndex('idx_a_placements_id', 'ads', 'placements_id');
        $this->addForeignKey('fk_a_placement', 'ads', 'placements_id', 'placements', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropColumn('users', 'phone_number');
        $this->dropColumn('ads', 'expiry_date');
        $this->dropColumn('ads', 'url');
        $this->dropForeignKey('fk_ads_placement','ads');
        $this->dropIndex('idx_a_placements_id','ads');
        $this->dropColumn('ads', 'placements_id');
    }
}
