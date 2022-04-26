<?php

namespace App\EventSubscriber;

use App\Utils\RedisKeys;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutSubscriber implements EventSubscriberInterface
{

    private \Redis $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    #[ArrayShape([LogoutEvent::class => "string"])] public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'onLogout'
        ];
    }

    public function onLogout(LogoutEvent $event)
    {
        $userId = $event->getToken()->getUserIdentifier();
        if (!empty($userId))
            $this->redis->del(RedisKeys::getSessionId($userId));
    }
}