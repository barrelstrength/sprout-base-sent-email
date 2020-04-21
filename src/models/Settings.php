<?php

namespace barrelstrength\sproutbasesentemail\models;

use barrelstrength\sproutbase\base\SharedPermissionsInterface;
use barrelstrength\sproutbase\base\SproutSettingsInterface;
use Craft;
use craft\base\Model;

/**
 * @property array $sharedPermissions
 * @property array $settingsNavItems
 */
class Settings extends Model implements SproutSettingsInterface, SharedPermissionsInterface
{
    /**
     * @var string
     */
    public $pluginNameOverride = '';

    /**
     * @var bool
     */
    public $enableSentEmails = false;

    /**
     * @var int
     */
    public $sentEmailsLimit = 5000;

    /**
     * @var int
     */
    public $cleanupProbability = 1000;

    /**
     * @inheritdoc
     */
    public function getSettingsNavItems(): array
    {
        return [
            'general' => [
                'label' => Craft::t('sprout-base-sent-email', 'General'),
                'url' => 'sprout-sent-email/settings/general',
                'selected' => 'general',
                'template' => 'sprout-base-sent-email/settings/general'
            ],
            'sent-email' => [
                'label' => Craft::t('sprout-base-sent-email', 'Sent Email'),
                'url' => 'sprout-sent-email/settings/sent-email',
                'selected' => 'sent-email',
                'template' => 'sprout-base-sent-email/settings/sent-email'
            ]
        ];
    }

    /**
     * Shared permissions they may be prefixed by another plugin. Before checking
     * these permissions the plugin name will be determined from the URL and appended.
     *
     * @return array
     * @example
     * /admin/sprout-reports/page => sproutReports-viewReports
     * /admin/sprout-forms/page => sproutForms-viewReports
     *
     */
    public function getSharedPermissions(): array
    {
        return [
            'viewSentEmail',
            'resendEmails'
        ];
    }
}