<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasesentemail\services;

use barrelstrength\sproutbase\SproutBase;
use barrelstrength\sproutbaseemail\SproutBaseEmail;
use barrelstrength\sproutbasesentemail\models\Settings as SproutBaseSentEmailSettings;
use barrelstrength\sproutbasesentemail\SproutBaseSentEmail;
use craft\base\Model;
use yii\base\Component;

/**
 *
 * @property null|Model                  $pluginSettings
 * @property SproutBaseSentEmailSettings $sentEmailSettings
 * @property int                         $descriptionLength
 */
class Settings extends Component
{
    /**
     * @return SproutBaseSentEmailSettings
     */
    public function getSentEmailSettings(): SproutBaseSentEmailSettings
    {
        /** @var SproutBaseSentEmailSettings $settings */
        $settings = SproutBase::$app->settings->getBaseSettings(SproutBaseSentEmailSettings::class, 'sprout-sent-email');

        return $settings;
    }

    /**
     * @return bool
     */
    public function isPro(): bool
    {
        $sproutSentEmailIsPro = SproutBase::$app->settings->isEdition('sprout-sent-email', SproutBaseSentEmail::EDITION_PRO);
        $sproutEmailIsPro = SproutBase::$app->settings->isEdition('sprout-email', SproutBaseEmail::EDITION_PRO);

        return $sproutEmailIsPro || $sproutSentEmailIsPro;
    }
}
