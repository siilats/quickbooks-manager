<?php

namespace Hotrush\QuickBooksManager;

use Illuminate\Database\Eloquent\Model;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2AccessToken;

class QuickBooksToken extends Model
{
    const TOKEN_LIFETIME = 3600;
    const TOKEN_REFRESH_PERIOD = 3000;
    const REFRESH_TOKEN_LIFETIME = 8726400;

    protected $table = 'quickbooks_tokens';

    protected $fillable = [
        'connection', 'access_token', 'refresh_token', 'realm_id',
        'issued_at', 'expire_at', 'refresh_at', 'refresh_expire_at',
    ];

    public $timestamps = false;

    protected $dates = [
        'issued_at', 'expire_at', 'refresh_at', 'refresh_expire_at',
    ];

    /**
     * @return bool
     */
    public function isExpired()
    {
        return $this->expire_at < now();
    }

    /**
     * @return bool
     */
    public function isRefreshable()
    {
        return $this->refresh_expire_at > now();
    }

    public static function createFromToken($connection, OAuth2AccessToken $token)
    {
        return self::create([
            'connection' => $connection,
            'access_token' => $token->getAccessToken(),
            'refresh_token' => $token->getRefreshToken(),
            'realm_id' => $token->getRealmID(),
            'issued_at' => now(),
            'expire_at' => now()->addSeconds(self::TOKEN_LIFETIME),
            'refresh_at' => now()->addSeconds(self::TOKEN_REFRESH_PERIOD),
            'refresh_expire_at' => now()->addSeconds(self::REFRESH_TOKEN_LIFETIME),
        ]);
    }

    public static function removeExpired($connection, $except = [])
    {
        return self::where('connection', $connection)->whereNotIn('id', $except)->delete();
    }
}