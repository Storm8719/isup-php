<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%sites}}`.
 */
class m221213_184101_add_image_url_options_and_is_image_setted_columns_to_sites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sites}}', 'image_url_options', $this->json()->after('image_url'));
        $this->addColumn('{{%sites}}', 'is_image_setted', $this->boolean()->after('image_url'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%sites}}', 'image_url_options');
        $this->dropColumn('{{%sites}}', 'is_image_setted');
    }
}
