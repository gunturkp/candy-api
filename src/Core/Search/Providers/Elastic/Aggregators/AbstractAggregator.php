<?php

namespace GetCandy\Api\Core\Search\Providers\Elastic\Aggregators;

abstract class AbstractAggregator
{
    /**
     * Get the aggregator
     *
     * @return mixed
     */
    abstract public function getQuery();
}