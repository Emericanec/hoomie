<?php

declare(strict_types=1);

namespace App\Service\Analytics\Entity;

use App\Entity\Page as DbPage;
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

    private DbPage $page;

    public function __construct(DbPage $page)
    {
        $this->client = ElasticSearch::getClient();
        $this->queryBuilder = new QueryBuilder();
        $this->page = $page;
    }

    public function getPageViewsByLast7Days(): array
    {
        $search = new Search($this->client);
        $search->addIndex(ElasticSearchIndex::INDEX_EVENTS);

        $range = $this->queryBuilder->query()->range(Event::PARAM_EVENT_CREATED, ['gte' => 'now-7d/d']);
        $filter = $this->queryBuilder->query()->bool()->addMust([
            $this->queryBuilder->query()->match(Event::PARAM_EVENT_TYPE, Event::TYPE_VISIT_PAGE),
            $this->queryBuilder->query()->match(Event::PARAM_EVENT_ID, $this->page->getId())
        ])->addFilter($range);

        $aggregation = $this->queryBuilder->aggregation()->date_histogram('histogram', Event::PARAM_EVENT_CREATED, 'day');
        $query = (new Query())->setQuery($filter)->addAggregation($aggregation);
        $search->setQuery($query);

        $result = $search->search();

        $resultStats = [];
        $index = 0;
        for ($i = 6; $i >= 0; $i--) {
            $date = (new DateTime())->modify("-{$i} days")->format('d.m.Y');
            $resultStats[$date] = [
                'label' => $date,
                'index' => $index,
                'count' => 0
            ];
            $index++;
        }

        foreach ($result->getAggregation('histogram') as $oneAggregate) {
            foreach ($oneAggregate as $day) {
                $date = date('d.m.Y', $day['key'] / 1000);
                $resultStats[$date]['count'] = $day['doc_count'];
            }
        }

        return $resultStats;
    }

    public function getTotalViews(): int
    {
        $aggregationName = 'value_count';

        $search = new Search($this->client);
        $search->addIndex(ElasticSearchIndex::INDEX_EVENTS);

        $filter = $this->queryBuilder->query()->bool()->addMust([
            $this->queryBuilder->query()->match(Event::PARAM_EVENT_TYPE, Event::TYPE_VISIT_PAGE),
            $this->queryBuilder->query()->match(Event::PARAM_EVENT_ID, $this->page->getId())
        ]);

        $aggregation = $this->queryBuilder->aggregation()->value_count($aggregationName, Event::PARAM_EVENT_ID);
        $query = (new Query())->setQuery($filter)->addAggregation($aggregation);
        $search->setQuery($query);

        $result = $search->search();
        return (int)($result->getAggregation($aggregationName)['value'] ?? 0);
    }

    public function getTotalClicks(): int
    {
        $aggregationName = 'value_count';

        $search = new Search($this->client);
        $search->addIndex(ElasticSearchIndex::INDEX_EVENTS);

        $args = [];
        foreach ($this->page->getLinks() as $link) {
            $args[] = $this->queryBuilder->query()->term()->setTerm(Event::PARAM_EVENT_ID, $link->getId());
        }

        $filter = $this->queryBuilder->query()->bool()->addMust([
            $this->queryBuilder->query()->match(Event::PARAM_EVENT_TYPE, Event::TYPE_VISIT_LINK),
            $this->queryBuilder->query()->bool()->addShould($args)->setMinimumShouldMatch(1)
        ]);

        $aggregation = $this->queryBuilder->aggregation()->value_count($aggregationName, Event::PARAM_EVENT_ID);
        $query = (new Query())->setQuery($filter)->addAggregation($aggregation);
        $search->setQuery($query);

        $result = $search->search();
        return (int)($result->getAggregation($aggregationName)['value'] ?? 0);
    }
}
