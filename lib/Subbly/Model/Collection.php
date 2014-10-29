<?php

namespace Subbly\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as BaseCollection;

class Collection extends BaseCollection
{
    /** @var Illuminate\Database\Eloquent\Builder $query */
    private $query;

    /**
     * The constructor.
     *
     * @param \Illuminate\Database\Eloquent\Builder  $query The Eloquent query
     */
    public function __construct(Builder $query)
    {
        $this->query = $query;
        $this->hydrate();
    }

    /**
     * Hydrate the datas
     */
    private function hydrate()
    {
        /**
         * count
         */
        $query = clone $this->query;

        foreach (array('orders', 'limit', 'offset') as $field) {
            $query->getQuery()->{$field} = null;
        }

        $this->total = $query->count();

        /**
         * entries
         */
        $query = clone $this->query;
        $this->entries = $query->get();

        $this->items = $this->entries->all();
    }

    /**
     * Get the total query entries without limit and offset
     *
     * @return int
     */
    public function total()
    {
        return (int) $this->total;
    }

    /**
     * Get the offset used for the query
     *
     * @return int
     */
    public function offset()
    {
        return (int) $this->query->getQuery()->offset;
    }

    /**
     * Get the limit used for the query
     *
     * @return int
     */
    public function limit()
    {
        return (int) $this->query->getQuery()->limit;
    }
}
