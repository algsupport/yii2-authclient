<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace algsupport\authclient\clients;

use algsupport\authclient\OAuth2;
use yii\helpers\Json;

/**
 * VKontakte allows authentication via VKontakte OAuth.
 *
 * In order to use VKontakte OAuth you must register your application at <https://vk.com/editapp?act=create>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'algsupport\authclient\Collection',
 *         'clients' => [
 *             'vkontakte' => [
 *                 'class' => 'algsupport\authclient\clients\VKontakte',
 *                 'clientId' => 'vkontakte_client_id',
 *                 'clientSecret' => 'vkontakte_client_secret',
 *                 'scope' => 'email'
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 * @see https://vk.com/editapp?act=create
 * @see https://vk.com/dev/users.get
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class VKontakte extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    public $authUrl = 'https://oauth.vk.com/authorize';
    /**
     * {@inheritdoc}
     */
    public $tokenUrl = 'https://oauth.vk.com/access_token';
    /**
     * {@inheritdoc}
     */
    public $apiBaseUrl = 'https://api.vk.com/method';
    /**
     * @var array list of attribute names, which should be requested from API to initialize user attributes.
     * @since 2.0.4
     */
    public $attributeNames = [
        'uid',
        'first_name',
        'last_name',
        'nickname',
        'screen_name',
        'sex',
        'bdate',
        'city',
        'country',
        'timezone',
        'photo'
    ];
    /**
     * @var string the API version to send in the API request.
     * @see https://vk.com/dev/versions
     * @since 2.1.4
     */
    public $apiVersion = '5.95';


    /**
     * {@inheritdoc}
     */
    protected function initUserAttributes()
    {
        $response = $this->api('users.get.json', 'GET', [
            'fields' => implode(',', $this->attributeNames),
        ]);

        if (empty($response['response'])) {
            throw new \RuntimeException('Unable to init user attributes due to unexpected response: ' . Json::encode($response));
        }

        $attributes = array_shift($response['response']);

        $accessToken = $this->getAccessToken();
        if (is_object($accessToken)) {
            $accessTokenParams = $accessToken->getParams();
            unset($accessTokenParams['access_token']);
            unset($accessTokenParams['expires_in']);
            $attributes = array_merge($accessTokenParams, $attributes);
        }

        return $attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        $data = $request->getData();
        $data['v'] = $this->apiVersion;
        $data['user_ids'] = $accessToken->getParam('user_id');
        $data['access_token'] = $accessToken->getToken();
        $request->setData($data);
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultName()
    {
        return 'vkontakte';
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultTitle()
    {
        return 'VKontakte';
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultNormalizeUserAttributeMap()
    {
        return [
            'id' => 'uid'
        ];
    }
}
