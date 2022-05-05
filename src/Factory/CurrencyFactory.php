<?php

namespace App\Factory;

use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Currency>
 *
 * @method static Currency|Proxy createOne(array $attributes = [])
 * @method static Currency[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Currency|Proxy find(object|array|mixed $criteria)
 * @method static Currency|Proxy findOrCreate(array $attributes)
 * @method static Currency|Proxy first(string $sortedField = 'id')
 * @method static Currency|Proxy last(string $sortedField = 'id')
 * @method static Currency|Proxy random(array $attributes = [])
 * @method static Currency|Proxy randomOrCreate(array $attributes = [])
 * @method static Currency[]|Proxy[] all()
 * @method static Currency[]|Proxy[] findBy(array $attributes)
 * @method static Currency[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Currency[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static CurrencyRepository|RepositoryProxy repository()
 * @method Currency|Proxy create(array|callable $attributes = [])
 */
final class CurrencyFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'currency' => self::faker()->currencyCode(),
            'currencyFullName' => self::faker()->currencyCode(),
            'symbol' => self::faker()->safeHexColor(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Currency $currency): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Currency::class;
    }
}
