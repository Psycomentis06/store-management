<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\RedisKeys;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class TokenFormAuthenticator extends AbstractAuthenticator
{

    private \Redis $redis;
    private UserRepository $userRepository;

    public function __construct(\Redis $redis, UserRepository $userRepository)
    {
        $this->redis = $redis;
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request): ?bool
    {
        if ($request->getMethod() === 'POST' && $request->getPathInfo() === '/auth/auto_auth') {
            return true;
        }
        return false;
    }

    public function authenticate(Request $request): Passport
    {
        $login = $request->request->get('_username');
        $token = $request->request->get('_login_token');
        $csrfToken = $request->request->get('_csrf_token');

        return new Passport(
            new UserBadge($login, function ($userIdentifier) {
                $user = $this->userRepository->findOneByIdOrUsernameOrEmail($userIdentifier);
                if (!$user instanceof UserInterface) {
                    throw new UserNotFoundException("User $userIdentifier not found");
                }
                return $user;
            }),
            new CustomCredentials(function ($credentials, User $user) use ($request) {
                $savedToken = $this->redis->get(RedisKeys::getAutoLoginToken($user->getUserIdentifier()));
                if (empty($savedToken)) return false;
                if ($savedToken !== $credentials) throw new \App\Exception\AuthenticationException("Wrong token given", \App\Exception\AuthenticationException::WRONG_TOKEN);
                return true;
            }, $token),
            [new CsrfTokenBadge('login', $csrfToken)]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse('/');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $login = $request->request->get('_username');
        $token = $request->request->get('_login_token');
        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        return new RedirectResponse("/auth/auto_auth?_username=$login&token=$token");
    }
}