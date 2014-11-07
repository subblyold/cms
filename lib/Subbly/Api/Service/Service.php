<?php

namespace Subbly\Api\Service;

use Subbly\Api\Api;
use Subbly\Model\ModelInterface;
use Subbly\Subbly;

abstract class Service
{
    /** @var Subbly\Api\Api $api **/
    private $api;

    /** @var string  The model class if the service is used for a model */
    protected $modelClass;

    /** @var array  The includable relationships */
    protected $includableRelationships;

    /**
     * The constructor.
     *
     * @param Subbly\Api\Api  $api The Api class
     *
     * @throws Subbly\Api\Service\Exception If name() method does not return a string
     */
    final public function __construct(Api $api)
    {
        if (!is_string($this->name())) {
            throw new Exception(sprintf('"%s"::name() method must return a string'),
                __CLASS__
            );
        }

        $this->api = $api;

        /**
         * Initialization
         */
        $this->fireEvent('initializing', array($this));

        $this->init();

        $this->fireEvent('initialized', array($this));
    }

    /**
     * Name of the service
     * Must be unique
     *
     * Example of value: 'subbly.user'
     *
     * @return string
     */
    abstract public function name();

    /**
     * Service initialization
     *
     * @api
     */
    protected function init() {}

    /**
     * Get new query instance
     *
     * @param array  $options
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function newQuery(array $options = array())
    {
        $options = array_replace(array(
            'includes' => array(),
        ), $options);

        $query = call_user_func($this->modelClass . '::query');

        /**
         * Includes
         */
        if (is_array($options['includes']))
        {
            $includes = array_values($options['includes']);

            foreach ($includes as $include)
            {
                if (in_array($include, $this->includableRelationships)) {
                    $query->with($include);
                }
            }
        }

        return $query;
    }

    /**
     * Get new collection query instance
     *
     * @param array  $options
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function newCollectionQuery(array $options = array())
    {
        $options = array_replace(array(
            'limit'  => null,
            'offset' => null,
        ), $options);

        $query = $this->newQuery($options);

        /**
         * Offset & limit
         */
        if (is_integer($options['limit'])) {
            $query->limit($options['limit']);
        }
        if (is_integer($options['offset']) && is_integer($options['limit'])) {
            $query->offset($options['offset']);
        }

        return $query;
    }

    /**
     * Get new search query instance
     *
     * @param string|array  $searchQuery      The search query
     * @param array         $searchableFields The searchable fields (default: all visible fields in the model)
     * @param string|null   $statmentsType
     * @param array         $options          The query options
     *
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * @throws \Subbly\Api\Service\Exception
     */
    protected function newSearchQuery($searchQuery, array $searchableFields = null, $statementsType = null, array $options = array())
    {
        // Query without scopes
        $instance = new $this->modelClass();
        $q        = $instance->newQueryWithoutScopes();

        if ($searchableFields === null || empty($searchableFields)) {
            $searchableFields = $instance->getVisible();
        }

        // If search query is a string
        if (is_string($searchQuery))
        {
            $st = strtoupper($statementsType) === 'AND'
                ? 'where'
                : 'orWhere'
            ;

            foreach ($searchableFields as $field) {
                $q->{$st}($field, 'LIKE', "%{$searchQuery}%");
            }
        }
        // If search query is an array
        else if (is_array($searchQuery))
        {
            $st = strtoupper($statementsType) === 'OR'
                ? 'orWhere'
                : 'where'
            ;

            foreach ($searchableFields as $field)
            {
                if (isset($searchQuery[$field])) {
                    $q->{$st}($field, 'LIKE', "%{$searchQuery[$field]}%");
                }
            }
        }
        else {
            throw new \ErrorException(sprintf('%s::%s() expects parameter 1 to be string or array, %s given',
                __CLASS__,
                __METHOD__,
                gettype($searchQuery)
            ));
        }

        return $this->newCollectionQuery($options)
            ->addNestedWhereQuery($q->getQuery())
        ;
    }

    /**
     * Fire an event
     *
     * @param string   $eventName Name of the event
     * @param array    $vars      Event vars
     * @param boolean  $halt
     *
     * @return array|null
     *
     * @api
     */
    protected function fireEvent($eventName, array $vars = array(), $halt = true)
    {
        $method    = $halt ? 'until' : 'fire';
        $eventName = sprintf('%s:%s', $this->name(), $eventName);

        return Subbly::events()->{$method}($eventName, $vars);
    }

    /**
     * Get the Api class
     *
     * @return \Subbly\Api\Api
     */
    final protected function api()
    {
        return $this->api;
    }
}
