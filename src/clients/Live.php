<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace algsupport\authclient\clients;

use algsupport\authclient\OAuth2;

/**
 * Live allows authentication via Microsoft Live OAuth.
 *
 * In order to use Microsoft Live OAuth you must register your application at <https://account.live.com/developers/applications>
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'algsupport\authclient\Collection',
 *         'clients' => [
 *             'live' => [
 *                 'class' => 'algsupport\authclient\clients\Live',
 *                 'clientId' => 'live_client_id',
 *                 'clientSecret' => 'live_client_secret',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 * @see https://account.live.com/developers/applications
 * @see https://msdn.microsoft.com/en-us/library/live/hh243647.aspx
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class Live extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    public $authUrl = 'https://login.live.com/oauth20_authorize.srf';
    /**
     * {@inheritdoc}
     */
    public $tokenUrl = 'https://login.live.com/oauth20_token.srf';
    /**
     * {@inheritdoc}
     */
    public $apiBaseUrl = 'https://apis.live.net/v5.0';


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(',', [
                'wl.basic',
                'wl.emails',
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function initUserAttributes()
    {
        return $this->api('me', 'GET');
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultName()
    {
        return 'live';
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultTitle()
    {
        return 'Live';
    }
}
