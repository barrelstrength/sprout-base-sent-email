<?php /** @noinspection ClassConstantCanBeUsedInspection */

namespace barrelstrength\sproutbasesentemail\migrations;

use craft\db\Migration;

class m200411_000000_update_sent_email_element_type_class extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $elements = [
            0 => [
                'oldType' => 'barrelstrength\sproutemail\elements\SentEmail',
                'newType' => 'barrelstrength\sproutbasesentemail\elements\SentEmail'
            ]
        ];

        foreach ($elements as $element) {
            $this->update('{{%elements}}', [
                'type' => $element['newType']
            ], ['type' => $element['oldType']], [], false);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        echo "m200411_000000_update_sent_email_element_type_class cannot be reverted.\n";

        return false;
    }
}
