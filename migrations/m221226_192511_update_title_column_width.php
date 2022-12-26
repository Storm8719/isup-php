<?php

use yii\db\Migration;

/**
 * Class m221226_192511_update_title_column_width
 */
class m221226_192511_update_title_column_width extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%sites}}', 'title', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221226_192511_update_title_column_width cannot be reverted.\n";
        $this->alterColumn('{{%sites}}', 'title', $this->string(128));
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221226_192511_update_title_column_width cannot be reverted.\n";

        return false;
    }
    */
}
