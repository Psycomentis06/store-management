<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Utils\RedisKeys;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Security;

class UserTokenValidatorSubscriber implements EventSubscriberInterface
{
    private Security $security;
    private \Redis $redis;

    public function __construct(Security $security, \Redis $redis)
    {
        $this->security = $security;
        $this->redis = $redis;
    }

    #[ArrayShape([RequestEvent::class => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onKernelRequest'
        ];
    }

    public function onKernelRequest(RequestEvent $requestEvent)
    {
        if ($this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->security->getToken()->getUser();
            if ($user instanceof User) {
                $lastLogin = $this->redis->get(RedisKeys::getLastLoginKey($user->getId()));
                if (!empty($lastLogin)) {
                    $userLastLogin = $user->getLastLogin()->format('Y-m-d H:i:s');
                    $lastSession = $this->redis->get(RedisKeys::getSessionId($this->security->getUser()->getUserIdentifier()));
                    $session = $requestEvent->getRequest()->getSession();
                    $sessionId = $session->getId();
                    $tokenLastLogin = $this->security->getToken()->getAttribute('last_login');
                    $firstTime = $this->security->getToken()->getAttribute('first_time');
                    $this->security->getToken()->setAttribute('clear_session_key', false);
                    if (($userLastLogin !== $tokenLastLogin))
                        $requestEvent->setResponse(new RedirectResponse('/auth/logout'));
                }
            }
        }
    }
}