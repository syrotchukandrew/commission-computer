<?php

declare(strict_types=1);

namespace App\Client;

use JsonException;

interface BinProviderInterface
{
    /**
     * @throws JsonException
     */
    public function getCountryByBin(string $bin): ?string;
}