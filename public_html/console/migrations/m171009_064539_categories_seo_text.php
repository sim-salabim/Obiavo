<?php

use yii\db\Migration;

class m171009_064539_categories_seo_text extends Migration
{
    public function safeUp()
    {
        $this->addColumn('categories_text', 'seo_text', $this->text());
        $this->addColumn('categories_text', 'seo_h2', $this->string());
        $this->addColumn('categories_text', 'seo_name', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn('categories_text', 'seo_h2');
        $this->dropColumn('categories_text', 'seo_name');
        $this->dropColumn('categories_text', 'seo_text');
    }

}
