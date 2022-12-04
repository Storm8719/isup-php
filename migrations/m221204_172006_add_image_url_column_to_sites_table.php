<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%sites}}`.
 */
class m221204_172006_add_image_url_column_to_sites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sites}}', 'image_url', $this->string(64)->after('info'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%sites}}', 'image_url');
    }
}
