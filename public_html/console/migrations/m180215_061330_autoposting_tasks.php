<?php

use yii\db\Migration;

class m180215_061330_autoposting_tasks extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('autoposting_tasks', [
            'id' => $this->primaryKey()->unsigned(),
            'ads_id' => $this->integer()->unsigned()->notNull(),
            'social_networks_groups_id' => $this->integer()->unsigned()->notNull(),
            'status' => $this->string()->notNull()->defaultValue(\common\models\AutopostingTasks::STATUS_PENDING),
            'priority' => $this->boolean()->defaultValue(false),
            'created_at' => $this->timestamp()->notNull(),
            'posted_at' => $this->timestamp()->null(),
            'supposed_at' => $this->timestamp()->null(),
        ], $tableOptions);

        $this->addCommentOnTable('autoposting_tasks', 'Таблица с задачами для автопостинга обьявлений в сообщества соцсетей');
        $this->addCommentOnColumn('autoposting_tasks','ads_id','ID обьявления');
        $this->addCommentOnColumn('autoposting_tasks','social_networks_groups_id','ID сообщества соцсети');
        $this->addCommentOnColumn('autoposting_tasks','status','Текущий статус задачи');
        $this->addCommentOnColumn('autoposting_tasks','priority','Приоритет выполнения задачи');
        $this->addCommentOnColumn('autoposting_tasks','created_at','Дата создания задачи');
        $this->addCommentOnColumn('autoposting_tasks','posted_at','Дата публикации обьявления в соцсообществе (если было опубликовано)');
        $this->addCommentOnColumn('autoposting_tasks','supposed_at','Предпологаемая дата публикации');

        $this->createIndex('idx_at_a_id', 'autoposting_tasks', 'ads_id');
        $this->addForeignKey('at_adsid_ibfk_1', 'autoposting_tasks', 'ads_id', 'ads', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_at_sngi_id', 'autoposting_tasks', 'social_networks_groups_id');
        $this->addForeignKey('at_sngid_ibfk_1', 'autoposting_tasks', 'social_networks_groups_id', 'social_networks_groups', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('at_adsid_ibfk_1','autoposting_tasks');
        $this->dropIndex('idx_at_a_id','autoposting_tasks');
        $this->dropForeignKey('at_sngid_ibfk_1','autoposting_tasks');
        $this->dropIndex('idx_at_sngi_id','autoposting_tasks');

        $this->dropTable('autoposting_tasks');
    }
}
