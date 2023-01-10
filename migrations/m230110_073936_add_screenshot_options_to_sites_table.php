<?php

use yii\db\Migration;

/**
 * Class m230110_073936_add_screenshot_options_to_sites_table
 */
class m230110_073936_add_screenshot_options_to_sites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sites}}', 'screenshot_url', $this->string(255)->null()->after('redirect_to'));
        $this->addColumn('{{%sites}}', 'screenshot_last_start_to_update', $this->integer(14)->null());
        $this->addColumn('{{%sites}}', 'screenshot_updated_at', $this->integer(14)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%sites}}', 'screenshot_url');
        $this->dropColumn('{{%sites}}', 'screenshot_last_start_to_update');
        $this->dropColumn('{{%sites}}', 'screenshot_updated_at');

        echo "m230110_073936_add_screenshot_options_to_sites_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230110_073936_add_screenshot_options_to_sites_table cannot be reverted.\n";

        return false;
    }
    */
}
