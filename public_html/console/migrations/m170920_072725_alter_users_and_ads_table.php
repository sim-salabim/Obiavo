<?php

use yii\db\Migration;

class m170920_072725_alter_users_and_ads_table extends Migration
{
    public function up()
    {
        $this->addColumn('users', 'phone_number', $this->string()->null());
        $this->addColumn('ads', 'expiry_date', $this->integer()->notNull());
    }

    public function down()
    {
        $this->dropColumn('users', 'phone_number');
        $this->dropColumn('ads', 'expiry_date');
    }
}
