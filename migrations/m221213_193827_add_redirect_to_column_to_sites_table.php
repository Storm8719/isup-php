<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%sites}}`.
 */
class m221213_193827_add_redirect_to_column_to_sites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sites}}', 'redirect_to', $this->string(255)->after('url'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%sites}}', 'redirect_to');
    }
}
