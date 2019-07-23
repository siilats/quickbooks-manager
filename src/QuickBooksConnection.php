<?php

namespace Hotrush\QuickBooksManager;

use Hotrush\QuickBooksManager\Http\Requests\AuthCallbackRequest;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2AccessToken;
use QuickBooksOnline\API\DataService\DataService;

class QuickBooksConnection
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $config;

    /**
     * @var QuickBooksToken
     */
    private $token;

    /**
     * @var DataService
     */
    private $client;

    /**
     * QuickBooksConnection constructor.
     * @param $name
     * @param array $config
     */
    public function __construct($name, array $config)
    {
        $this->name = $name;
        $this->config = $config;
        $this->token = $this->loadTokenFromDatabase();

        $this->initClient();
    }

    private function initClient()
    {
        $this->client = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => $this->config['client_id'],
            'ClientSecret' => $this->config['client_secret'],
            'RedirectURI' => route(config('quickbooks_manager.callback_route'), ['connection' => $this->name]),
            'scope' => $this->config['scope'],
            'baseUrl' => $this->config['base_url'],
            'accessTokenKey' => $this->token ? $this->token->access_token : null,
            'refreshTokenKey' => $this->token ? $this->token->refresh_token : null,
        ])
            ->setLogLocation($this->config['logs_path'])
            ->throwExceptionOnError(true);

        if ($this->token->isExpired()) {
            $this->refreshToken();
        }
    }

    /**
     * @return string
     */
    public function getAuthorizationRedirectUrl()
    {
        return $this->client->getOAuth2LoginHelper()->getAuthorizationCodeURL();
    }

    /**
     * @param AuthCallbackRequest $request
     * @throws \QuickBooksOnline\API\Exception\SdkException
     * @throws \QuickBooksOnline\API\Exception\ServiceException
     */
    public function handleAuthorizationCallback(AuthCallbackRequest $request)
    {
        $accessToken = $this->client
            ->getOAuth2LoginHelper()
            ->exchangeAuthorizationCodeForToken(
                $request->get('code'),
                $request->get('realmid')
            );

        $this->updateAccessToken($accessToken);
    }

    /**
     * @return QuickBooksToken
     */
    private function loadTokenFromDatabase()
    {
        return QuickBooksToken::where('connection', $this->name)
            ->orderBy('issued_at', 'desc')
            ->first();
    }

    /**
     * @throws \QuickBooksOnline\API\Exception\ServiceException
     */
    private function refreshToken()
    {
        $accessToken = $this->client->getOAuth2LoginHelper()->refreshToken();

        $this->updateAccessToken($accessToken);
    }

    /**
     * @param OAuth2AccessToken $accessToken
     */
    private function updateAccessToken(OAuth2AccessToken $accessToken)
    {
        $this->client->updateOAuth2Token($accessToken);

        $this->token = QuickBooksToken::createFromToken($this->name, $accessToken);
    }

    /**
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->client->$method(...$parameters);
    }
}