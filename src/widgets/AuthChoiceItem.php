<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace algsupport\authclient\widgets;

use yii\base\Widget;

/**
 * AuthChoiceItem is a base class for creating widgets, which can be used to render link
 * for auth client at [[AuthChoice]].
 *
 * @see AuthChoice
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class AuthChoiceItem extends Widget
{
    /**
     * @var \algsupport\authclient\ClientInterface auth client instance.
     */
    public $client;
    /**
     * @var AuthChoice parent AuthChoice widget
     */
    public $authChoice;
}
