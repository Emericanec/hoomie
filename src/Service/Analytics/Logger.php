<?php

declare(strict_types=1);

namespace App\Service\Analytics;

use App\Enum\Error;
use App\Service\ElasticSearch\ElasticSearchEvents;
use App\Traits\RollBarTrait;
use Throwable;

class Logger
{
    use RollBarTrait;

    private static ?ElasticSearchEvents $elasticEvents = null;

    public static function log(Event $event): void
    {
        self::getElasticClient()->logEvent($event);
    }

    public static function logVisitPage(int $userId, int $pageId): void
    {
        try {
            $event = new Event($userId, Event::TYPE_VISIT_PAGE, $pageId);
            self::log($event);
        } catch (Throwable $exception) {
            self::logger()->error(Error::ELASTIC_SEARCH_EVENT_LOG_VISIT_PAGE_ERROR, [
                'errorMessage' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
        }
    }

    public static function logVisitLink(int $userId, int $linkId): void
    {
        try {
            $event = new Event($userId, Event::TYPE_VISIT_LINK, $linkId);
            self::log($event);
        } catch (Throwable $exception) {
            self::logger()->error(Error::ELASTIC_SEARCH_EVENT_LOG_VISIT_LINK_ERROR, [
                'errorMessage' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
        }
    }

    private static function getElasticClient(): ElasticSearchEvents
    {
        if (null === self::$elasticEvents) {
            self::$elasticEvents = new ElasticSearchEvents();
        }

        return self::$elasticEvents;
    }
}
