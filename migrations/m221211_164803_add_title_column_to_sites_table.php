<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%sites}}`.
 */
class m221211_164803_add_title_column_to_sites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sites}}', 'title', $this->string(128)->after('header'));
        $this->addColumn('{{%sites}}', 'scheme', $this->string(8)->after('url'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%sites}}', 'image_url');
        $this->dropColumn('{{%sites}}', 'scheme');
    }
}
