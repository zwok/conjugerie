<?php

namespace App\Providers;

use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class SmartschoolSocialiteProvider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'SMARTSCHOOL';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://' . $this->config['platform'] . '/OAuth',
            $state
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://oauth.smartschool.be/OAuth/index/token';
    }

    public function getAccessToken($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'form_params' => $this->getTokenFields($code),
        ]);

        $this->credentialsResponseBody = json_decode($response->getBody()->getContents(), true);
        return $this->parseAccessToken($response->getBody());
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $responseUser = $this->getHttpClient()->get("https://oauth.smartschool.be/Api/V1/userinfo", [
            'query' => [
                'access_token' => $token,
            ],
        ]);

        $responseGroup = $this->getHttpClient()->get("https://oauth.smartschool.be/Api/V1/groupinfo", [
            'query' => [
                'access_token' => $token,
            ],
        ]);

        $data = json_decode($responseUser->getBody()->getContents(), true);
        $data['groups'] = json_decode($responseGroup->getBody()->getContents(), true)['groups'];

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user);
    }

    public static function additionalConfigKeys()
    {
        return ['platform'];
    }
}
