<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class NumberToTimeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('number_to_time', [$this, 'numberToTime']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('number_to_time', [$this, 'numberToTime'])
        ];
    }

    public function numberToTime($value): string
    {
        if ($value >= 10)
            return $value .':00';
        else
            return '0' . $value . ':00';
    }
}
