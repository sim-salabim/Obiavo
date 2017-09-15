<?php

use yii\db\Migration;

class m161028_122652_attributes_types extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('attributes_types', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(100)->notNull(),
            'description' => $this->string(),
        ], $tableOptions);

        $this->addcommentOnColumn('attributes_types','name','Уникальное имя для типа ');

        $this->addCommentOnTable('attributes_types', 'Список типов(текст, список и тд) для аттрибутов, которые указываются при подаче объявления');
    }

    public function safeDown()
    {
        $this->dropTable('attributes_types');
    }
}
