<?php

namespace App\Security;

use App\Exception\AuthenticationException;
use App\Repository\UserRepository;
use App\Utils\RedisKeys;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    private UserRepository $userRepository;
    private \Redis $redis;

    public function __construct(UserRepository $userRepository, \Redis $redis)
    {
        $this->userRepository = $userRepository;
        $this->redis = $redis;
    }

    public function authenticate(Request $request): Passport
    {
        # Login can be user's id, email or username
        $login = $request->request->get('_username');
        $password = $request->request->get('_password');
        $csrfToken = $request->request->get('_csrf_token');

        return new Passport(
            new UserBadge($login),
            new CustomCredentials(function ($credentials, UserInterface $user) use ($request) {
                // 1- Check password
                $passwordPassport = new PasswordCredentials($credentials);
                if (!$passwordPassport->isResolved()) throw new AuthenticationException("Wrong Password", AuthenticationException::WRONG_PASSWORD);

                // 2- Check if there is session already
                if (!empty($this->redis->get(RedisKeys::getSessionId($user->getUserIdentifier())))) throw new AuthenticationException('User Already logged in from other device', AuthenticationException::SINGLE_SESSION);

                // 3- Check if account locked
                $lockedRedisKey = RedisKeys::getLoginLocked($user->getUserIdentifier());
                $locked = $this->redis->get($lockedRedisKey);
                $lockedRemainDuration = 0;

                if (!empty($locked)) {
                    $lockedRemainDuration = $this->redis->getTimeout($lockedRedisKey);
                    throw new AuthenticationException("Account locked for $lockedRemainDuration seconds due to many failed login attempts");
                }

                // Set session last ID
                $request->request->set('userId', $user->getUserIdentifier());

                return true;
            }, $password),
            [
                new CsrfTokenBadge('login', $csrfToken)
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $this->redis->set(RedisKeys::getSessionId($request->request->get('userId')), $request->getSession()->getId());
        return null;
    }

    protected function getLoginUrl(Request $request): string
    {
        return '/auth/login';
    }
}