<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use App\Entity\User;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;


/**
 * @see https://symfony.com/doc/current/security/custom_authenticator.html
 */
abstract class SSOAuthenticator extends OAuth2Authenticator
{
    protected string $serviceSsoName = '';

    public function __construct(
        private readonly RouterInterface $router,
        private readonly ClientRegistry  $clientRegistry,
        private readonly UserRepository $userRepository,
    )
    {
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        return 'app_sso_connect' === $request->attributes->get('_route')
            && $request->get('service') === $this->serviceSsoName;
    }

    public function authenticate(Request $request): Passport
    {
        $credentials = $this->fetchAccessToken($this->getClient());
        $resourceOwner = $this->getResourceOwnerFromCredentials($credentials);

        $user = $this->getUserFormResourceOwner($resourceOwner, $this->userRepository);

        return new SelfValidatingPassport(
            userBadge: new UserBadge($user->getUserIdentifier(),fn () => $user),
            badges: [
                new RememberMeBadge()
            ]
        );
    }


    protected function getResourceOwnerFromCredentials(AccessToken $credentials): ResourceOwnerInterface
    {
        $this->getClient()->fetchUserFromToken($credentials);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {

        return new RedirectResponse($this->router->generate('app_dashboard'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    private function getClient() {
        return $this->clientRegistry->getClient($this->serviceSsoName);
    }

    abstract protected function getUserFormResourceOwner (ResourceOwnerInterface $resourceOwner, UserRepository $userRepository): ?User;
}
