<?php

namespace App\Factory;

use App\Entity\WorkEvent;
use App\Repository\WorkEventRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<WorkEvent>
 *
 * @method static WorkEvent|Proxy createOne(array $attributes = [])
 * @method static WorkEvent[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static WorkEvent|Proxy find(object|array|mixed $criteria)
 * @method static WorkEvent|Proxy findOrCreate(array $attributes)
 * @method static WorkEvent|Proxy first(string $sortedField = 'id')
 * @method static WorkEvent|Proxy last(string $sortedField = 'id')
 * @method static WorkEvent|Proxy random(array $attributes = [])
 * @method static WorkEvent|Proxy randomOrCreate(array $attributes = [])
 * @method static WorkEvent[]|Proxy[] all()
 * @method static WorkEvent[]|Proxy[] findBy(array $attributes)
 * @method static WorkEvent[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static WorkEvent[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static WorkEventRepository|RepositoryProxy repository()
 * @method WorkEvent|Proxy create(array|callable $attributes = [])
 */
final class WorkEventFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected static function getClass(): string
    {
        return WorkEvent::class;
    }

    protected function getDefaults(): array
    {
        return [
            'fromDate' => self::faker()->datetime(),
            'toDate' => self::faker()->datetime(),
            'type' => self::faker()->currencyCode(),
            'title' => self::faker()->text(20),
            'description' => self::faker()->text(20),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this// ->afterInstantiate(function(WorkEvent $workEvent): void {})
            ;
    }
}
