<?php

declare(strict_types=1);

namespace App\Service\ElasticSearch;

use Elastica\Client;

class ElasticSearch
{
    private static ?Client $client = null;

    public static function getClient(): Client
    {
        if (null === self::$client) {
            self::$client = new Client([
                'host' => self::getHost(),
                'port' => self::getPort(),
                'transport' => 'https',
                'username' => self::getUsername(),
                'password' => self::getPassword(),
            ]);
        }

        return self::$client;
    }

    private static function getHost(): ?string
    {
        return $_SERVER['ELASTIC_SEARCH_HOST'] ?? null;
    }

    private static function getPort(): ?string
    {
        return $_SERVER['ELASTIC_SEARCH_PORT'] ?? null;
    }

    private static function getUsername(): ?string
    {
        return $_SERVER['ELASTIC_SEARCH_USERNAME'] ?? null;
    }

    private static function getPassword(): ?string
    {
        return $_SERVER['ELASTIC_SEARCH_PASSWORD'] ?? null;
    }
}
