<?php

declare(strict_types=1);

namespace App\Service\Analytics\Entity;

use App\Service\Analytics\Event;
use App\Service\ElasticSearch\ElasticSearch;
use App\Service\ElasticSearch\ElasticSearchIndex;
use DateTime;
use Elastica\Client;
use Elastica\Query;
use Elastica\QueryBuilder;
use Elastica\Search;

class Page
{
    private Client $client;
    private QueryBuilder $queryBuilder;

    public function __construct()
    {
        $this->client = ElasticSearch::getClient();
        $this->queryBuilder = new QueryBuilder();
    }

    public function getPageViewsByLast7Days(int $pageId): array
    {
        $search = new Search($this->client);
        $search->addIndex(ElasticSearchIndex::INDEX_EVENTS);

        $range = $this->queryBuilder->query()->range(Event::PARAM_EVENT_CREATED, [
            'gte' => 'now-1d/d'
        ]);
        $filter = $this->queryBuilder->query()->bool()->addMust([
            $this->queryBuilder->query()->match(Event::PARAM_EVENT_TYPE, Event::TYPE_VISIT_PAGE),
            $this->queryBuilder->query()->match(Event::PARAM_EVENT_ID, $pageId)
        ])->addFilter($range);

        /** @phpstan-ignore-next-line */
        $aggregation = $this->queryBuilder->aggregation()->date_histogram('histogram', Event::PARAM_EVENT_CREATED, 'day');
        $query = (new Query())->setQuery($filter)->addAggregation($aggregation);
        $search->setQuery($query);

        $result = $search->search();

        $resultStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = (new DateTime())->modify("-{$i} days")->format('d.m.Y');
            $resultStats[$date] = 0;
        }

        foreach ($result->getAggregation('histogram') as $aggr) {
            foreach ($aggr as $day) {
                $date = date('d.m.Y',$day['key'] / 1000);
                $resultStats[$date] = $day['doc_count'];
            }
        }

        return $resultStats;
    }
}
