<?php
/**
 * @link https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license https://craftcms.github.io/license
 */

namespace barrelstrength\sproutbasesentemail\migrations;

use craft\db\Migration;

class m200521_000000_update_htmlBody_column_type extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $table = '{{%sproutemail_sentemail}}';

        if ($this->db->columnExists($table, 'htmlBody')) {
            // No need to worry about Postgres as the 'text' data type will handle both scenarios
            $this->alterColumn($table, 'htmlBody', $this->mediumText());
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m200521_000000_update_htmlBody_column_type cannot be reverted.\n";

        return false;
    }
}
