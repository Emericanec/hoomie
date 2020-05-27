<?php

declare(strict_types=1);

namespace App\Service\Analytics\Entity;

use App\Entity\Node;
use App\Service\Analytics\Event;
use App\Service\ElasticSearch\ElasticSearch;
use App\Service\ElasticSearch\ElasticSearchIndex;
use Elastica\Client;
use Elastica\Query;
use Elastica\QueryBuilder;
use Elastica\Search;

class Link
{
    private Client $client;

    private QueryBuilder $queryBuilder;

    private Node $node;

    public function __construct(Node $node)
    {
        $this->client = ElasticSearch::getClient();
        $this->queryBuilder = new QueryBuilder();
        $this->node = $node;
    }

    public function getTotalClicks(): int
    {
        $aggregationName = 'value_count';

        $search = new Search($this->client);
        $search->addIndex(ElasticSearchIndex::INDEX_EVENTS);

        $filter = $this->queryBuilder->query()->bool()->addMust([
            $this->queryBuilder->query()->match(Event::PARAM_EVENT_TYPE, Event::TYPE_VISIT_LINK),
            $this->queryBuilder->query()->match(Event::PARAM_EVENT_ID, $this->node->getId())
        ]);

        $aggregation = $this->queryBuilder->aggregation()->value_count($aggregationName, Event::PARAM_EVENT_ID);
        $query = (new Query())->setQuery($filter)->addAggregation($aggregation);
        $search->setQuery($query);

        $result = $search->search();
        return (int)($result->getAggregation($aggregationName)['value'] ?? 0);
    }
}
