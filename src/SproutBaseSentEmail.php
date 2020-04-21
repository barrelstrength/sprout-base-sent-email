<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com/
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   http://sprout.barrelstrengthdesign.com/license
 */

namespace barrelstrength\sproutbasesentemail;

use barrelstrength\sproutbase\SproutBase;
use barrelstrength\sproutbase\SproutBaseHelper;
use barrelstrength\sproutbaseemail\SproutBaseEmailHelper;
use barrelstrength\sproutbasesentemail\controllers\SentEmailController;
use barrelstrength\sproutbasesentemail\models\Settings as SproutBaseSentEmailSettings;
use barrelstrength\sproutbasesentemail\services\App;
use Craft;
use craft\events\RegisterTemplateRootsEvent;
use craft\helpers\ArrayHelper;
use craft\i18n\PhpMessageSource;
use craft\web\View;
use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\base\Module;
use yii\mail\BaseMailer;
use yii\mail\MailEvent;

/**
 * @property mixed $cpNavItem
 * @property array $cpUrlRules
 * @property array $sproutDependencies
 * @property array $siteUrlRules
 */
class SproutBaseSentEmail extends Module
{
    /**
     * This Pro Edition value will be used to test for all pro plugins:
     * - Sprout Email Pro
     * - Sprout Sent Email Pro
     */
    const EDITION_PRO = 'pro';

    /**
     * Enable use of SproutBaseSentEmail::$app-> in place of Craft::$app->
     *
     * @var App
     */
    public static $app;

    /**
     * @var string
     */
    public $handle;

    /**
     * @var string|null The translation category that this module translation messages should use. Defaults to the lowercase plugin handle.
     */
    public $t9nCategory;

    /**
     * @var string The language that the module messages were written in
     */
    public $sourceLanguage = 'en-US';

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, array $config = [])
    {
        // Set some things early in case there are any settings, and the settings model's
        // init() method needs to call Craft::t() or Plugin::getInstance().

        $this->handle = 'sprout-base-sent-email';
        $this->t9nCategory = ArrayHelper::remove($config, 't9nCategory', $this->t9nCategory ?? strtolower($this->handle));
        $this->sourceLanguage = ArrayHelper::remove($config, 'sourceLanguage', $this->sourceLanguage);

        if (($basePath = ArrayHelper::remove($config, 'basePath')) !== null) {
            $this->setBasePath($basePath);
        }

        // Translation category
        $i18n = Craft::$app->getI18n();
        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (!isset($i18n->translations[$this->t9nCategory]) && !isset($i18n->translations[$this->t9nCategory.'*'])) {
            $i18n->translations[$this->t9nCategory] = [
                'class' => PhpMessageSource::class,
                'sourceLanguage' => $this->sourceLanguage,
                'basePath' => $this->getBasePath().DIRECTORY_SEPARATOR.'translations',
                'allowOverrides' => true,
            ];
        }

        // Set this as the global instance of this plugin class
        static::setInstance($this);

        parent::__construct($id, $parent, $config);
    }

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        SproutBaseHelper::registerModule();
        SproutBaseEmailHelper::registerModule();

        $this->setComponents([
            'app' => App::class
        ]);

        self::$app = $this->get('app');

        Craft::setAlias('@sproutbasesentemail', $this->getBasePath());

        // Setup Controllers
        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            $this->controllerNamespace = 'sproutbasesentemail\\console\\controllers';
        } else {
            $this->controllerNamespace = 'sproutbasesentemail\\controllers';

            $this->controllerMap = [
                'sent-email' => SentEmailController::class
            ];
        }

        // Email Tracking
        Event::on(BaseMailer::class, BaseMailer::EVENT_AFTER_SEND, static function(MailEvent $event) {
            $sentEmailSettings = SproutBaseSentEmail::$app->settings->getSentEmailSettings();
            if ($sentEmailSettings->enableSentEmails) {
                SproutBaseSentEmail::$app->sentEmails->logSentEmail($event);
            }
        });

        // Setup Template Roots
        Event::on(View::class, View::EVENT_REGISTER_CP_TEMPLATE_ROOTS, function(RegisterTemplateRootsEvent $e) {
            $e->roots['sprout-base-sent-email'] = $this->getBasePath().DIRECTORY_SEPARATOR.'templates';
        });
    }
}
