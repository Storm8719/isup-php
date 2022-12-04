<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sites}}`.
 */
class m221204_160036_create_sites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sites}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string()->notNull(),
            'status' => $this->integer()->notNull(),
            'header' => $this->text()->null(),
            'description' => $this->text()->null(),
            'last_http_code' => $this->integer()->null(),
            'ttfb' => $this->integer()->null(),
            'pagesize' => $this->integer()->null(),
            'info' => $this->text()->null(),
            'additional_content' => $this->text()->null(),
            'is_need_check_flag' => $this->boolean()->null(),
            'created_by' => $this->integer()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sites}}');
    }
}
