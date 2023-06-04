<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(private \Redis $redis) {}

    public function createForUser(User $user): false|string
    {
        $accessToken = session_create_id();
        $this->redis->setex("sessions/{$accessToken}", 4*60*60, $user->getUserIdentifier());
        return $accessToken;
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $userId = $this->redis->get("sessions/{$accessToken}");
        if (!$userId)
            throw new BadCredentialsException('Invalid token');
        return  new UserBadge($userId);
    }
}