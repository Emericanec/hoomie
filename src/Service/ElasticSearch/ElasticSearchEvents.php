<?php

declare(strict_types=1);

namespace App\Service\ElasticSearch;

use Elastica\Bulk\ResponseSet;
use Elastica\Client;
use Elastica\Document;

class ElasticSearchEvents
{
    private Client $client;

    public function __construct()
    {
        $this->client = ElasticSearch::getClient();
    }

    public function logEvent(EventInterface $event): ResponseSet
    {
        $doc = new Document();
        $doc->setIndex(ElasticSearchIndex::INDEX_EVENTS);
        $doc->setData($event->getData());
        return $this->client->addDocuments([$doc]);
    }
}
