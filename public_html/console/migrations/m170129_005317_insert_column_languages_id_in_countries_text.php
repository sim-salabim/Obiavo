<?php

use yii\db\Migration;

class m170129_005317_insert_column_languages_id_in_countries_text extends Migration
{
    public function up()
    {
        $this->addColumn('countries_text', 'languages_id', $this->integer()->unsigned()->notNull()->after('id'));

        $this->addForeignKey('fk_countries_text_languages', 'countries_text', 'languages_id', 'languages', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_countries_text_languages', 'countries_text');
        $this->dropColumn('countries_text', 'languages_id');
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
