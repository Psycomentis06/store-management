<?php

namespace App\Security;

use App\Entity\User;
use App\Exception\AuthenticationException;
use App\Repository\UserRepository;
use App\Utils\RedisKeys;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    private KernelInterface $kernel;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $userRepository, \Redis $redis, KernelInterface $kernel, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->redis = $redis;
        $this->kernel = $kernel;
        $this->passwordHasher = $passwordHasher;
    }

    public function authenticate(Request $request): Passport
    {
        # Login can be user's id, email or username
        $login = $request->request->get('_username');
        $password = $request->request->get('_password');
        $csrfToken = $request->request->get('_csrf_token');

        return new Passport(
            new UserBadge($login),
            new CustomCredentials(function ($credentials, User $user) use ($request) {
                // 1- Check if account locked
                $lockedRedisKey = RedisKeys::getLoginLocked($user->getUserIdentifier());
                $locked = $this->redis->get($lockedRedisKey);

                if (!empty($locked)) {
                    $lockedRemainDuration = $this->redis->ttl($lockedRedisKey);
                    $lockedRemainDurationMin = round($lockedRemainDuration / 60);
                    throw new AuthenticationException("Account locked for $lockedRemainDuration seconds | $lockedRemainDurationMin minutes due to many failed login attempts");
                }

                // 2- Check password
                $isPassValid = $this->passwordHasher->isPasswordValid($user, $credentials);
                if (!$isPassValid) {
                    $container = $this->kernel->getContainer();
                    $failedAttemptsLimit = $container->getParameter('app.login_attempts');
                    $lockedTimesScale = $container->getParameter('app.login_lock_scale');
                    $lockDuration = $container->getParameter('app.login_lock_duration');
                    $failedAttemptsExpiration = $container->getParameter('app.login_attempts_expire');
                    $storedFailedAttempts = $this->redis->get(RedisKeys::getLoginFailedAttempts($user->getUserIdentifier()));
                    $storedFailedAttempts = empty($storedFailedAttempts) ? 0 : $storedFailedAttempts ;
                    $lockedTimes = $this->redis->get(RedisKeys::getLoginLockedTimes($user->getUserIdentifier()));
                    $lockedTimes = empty($lockedTimes) ? 0 : $lockedTimes;
                    // True means lock the account
                    if ($storedFailedAttempts >= $failedAttemptsLimit) {
                        $lockedTimes++;
                        $lockTimesExpiration = $container->getParameter('app.login_lock_scale_expire') * $lockedTimesScale;
                        $lockTimesExpiration = $lockedTimes == 0 ? $lockTimesExpiration : $lockTimesExpiration * $lockedTimes;
                        $this->redis->set(RedisKeys::getLoginLockedTimes($user->getUserIdentifier()), $lockedTimes, ['EX' => $lockTimesExpiration]);
                        $lockExp = $lockedTimes === 0 ? $lockDuration * 1 * $lockedTimesScale : $lockDuration * $lockedTimes * $lockedTimesScale;
                        $this->redis->set($lockedRedisKey, true, ['EX' => $lockExp]);
                    } else {
                        $storedFailedAttempts++;
                        $lockAttemptsExp = $failedAttemptsExpiration * $lockedTimesScale;
                        $lockAttemptsExp = $lockedTimes === 0 ? $lockAttemptsExp : $lockAttemptsExp * $lockedTimes;
                        $this->redis->set(RedisKeys::getLoginFailedAttempts($user->getUserIdentifier()), $storedFailedAttempts, ['EX' => $lockAttemptsExp]);
                    }
                    throw new AuthenticationException("Wrong Password ($storedFailedAttempts/$failedAttemptsLimit) attempts remaining", AuthenticationException::WRONG_PASSWORD);
                }

                // 3- Check if there is session already
                if (!empty($this->redis->get(RedisKeys::getSessionId($user->getUserIdentifier())))) throw new AuthenticationException('User Already logged in from other device', AuthenticationException::SINGLE_SESSION);

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