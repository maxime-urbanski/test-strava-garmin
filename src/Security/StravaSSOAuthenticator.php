<?php

namespace App\Security;

use App\Repository\UserRepository;
use App\Entity\User;
use http\Exception\RuntimeException;
use League\OAuth2\Client\Provider\StravaUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
class StravaSSOAuthenticator extends SSOAuthenticator
{
    protected string $serviceSsoName  = 'strava';

    protected function getUserFormResourceOwner (ResourceOwnerInterface $resourceOwner, UserRepository $userRepository): ?User
    {
        if (!($resourceOwner instanceof StravaUser)) {
            throw new RuntimeException("Resource owner must be an instance of StravaUser");
        }

        return $userRepository->findOneBy([
            'strava_id' => $resourceOwner->getId(),
        ]);
    }
}
