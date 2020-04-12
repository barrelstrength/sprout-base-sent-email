<?php
/**
 * @link      https://sprout.barrelstrengthdesign.com
 * @copyright Copyright (c) Barrel Strength Design LLC
 * @license   https://craftcms.github.io/license
 */

namespace barrelstrength\sproutbasesentemail\services;

use craft\base\Component;

class App extends Component
{
    /**
     * @var SentEmails
     */
    public $sentEmails;

    public function init()
    {
        $this->sentEmails = new SentEmails();
    }
}
