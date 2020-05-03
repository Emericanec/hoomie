<?php

declare(strict_types=1);

namespace App\Service\Analytics;

use App\Service\ElasticSearch\EventInterface;
use DateTime;

class Event implements EventInterface
{
    public const TYPE_VISIT_PAGE = 1;
    public const TYPE_VISIT_LINK = 2;

    public const PARAM_USER_ID = 'user_id';
    public const PARAM_EVENT_TYPE = 'event_type';
    public const PARAM_EVENT_ID = 'event_id';
    public const PARAM_EVENT_CREATED = 'event_created';

    private int $entityId;

    private int $entityType;

    private int $userId;

    public function __construct(int $userId, int $entityType, int $entityId)
    {
        $this->userId = $userId;
        $this->entityType = $entityType;
        $this->entityId = $entityId;
    }

    public function getData(): array
    {
        return [
            self::PARAM_USER_ID => $this->userId,
            self::PARAM_EVENT_TYPE => $this->entityType,
            self::PARAM_EVENT_ID => $this->entityId,
            self::PARAM_EVENT_CREATED => (new DateTime())->format(DATE_ATOM),
        ];
    }
}
