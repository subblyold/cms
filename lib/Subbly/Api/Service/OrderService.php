<?php

namespace Subbly\Api\Service;

use Subbly\Model\Collection;
use Subbly\Model\Order;

class OrderService extends Service
{
    protected $modelClass = 'Subbly\\Model\\Order';

    protected $includableRelationships = array('shipping_address', 'billing_address', 'products');

    /**
     * Return an empty model
     *
     * @return \Subbly\Model\Order
     *
     * @api
     */
    public function newOrder()
    {
        return new Order();
    }

    /**
     * Get all Order
     *
     * @param array $options
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * @api
     */
    public function all(array $options = array())
    {
        $query = $this->newCollectionQuery($options);

        return new Collection($query);
    }

    /**
     * Find a Order by $id
     *
     * @example
     *     $order = Subbly::api('subbly.order')->find($id);
     *
     * @param string  $id
     * @param array   $options
     *
     * @return \Subbly\Model\Order
     *
     * @api
     */
    public function find($id, array $options = array())
    {
        $options = array_replace(array(
            'includes' => array('images', 'categories', 'options'),
        ), $options);

        $query = $this->newQuery($options);
        $query->where('id', '=', $id);

        return $query->firstOrFail();
    }

    /**
     * Search a Order by options
     *
     * @example
     *     $orders = Subbly::api('subbly.order')->searchBy(array(
     *         'status' => Order::STATUS_DRAFT,
     *     ));
     *     // OR
     *     $orders = Subbly::api('subbly.order')->searchBy('some words');
     *
     * @param array|string  $searchQuery    Search params
     * @param array         $options        Query options
     * @param string        $statementsType Type of statement null|or|and (default is null)
     *
     * @return \Subbly\Model\Collection
     *
     * @api
     */
    public function searchBy($searchQuery, array $options = array(), $statementsType = null)
    {
        $query = $this->newSearchQuery($searchQuery, array(
            'status',
        ), $statementsType, $options);

        return new Collection($query);
    }

    /**
     * Create a new Order
     *
     * @example
     *     $order = Subbly\Model\Order;
     *     Subbly::api('subbly.order')->create($order);
     *
     *     Subbly::api('subbly.order')->create(array(
     *         'firstname' => 'Jon',
     *         'lastname'  => 'Snow',
     *     ));
     *
     * @param \Subbly\Model\Order|array $order
     *
     * @return \Subbly\Model\Order
     *
     * @api
     */
    public function create($order)
    {
        if (is_array($order)) {
            $order = new Order($order);
        }

        if ($order instanceof Order)
        {
            if ($this->fireEvent('creating', array($order)) === false) return false;

            $order->setCaller($this);
            $order->save();

            $this->fireEvent('created', array($order));

            $order = $this->find($order->id);

            return $order;
        }

        throw new Exception(sprintf(Exception::CANT_CREATE_MODEL,
            'Subbly\\Model\\Order',
            $this->name()
        ));
    }

    /**
     * Update a Order
     *
     * @example
     *     $order = [Subbly\Model\Order instance];
     *     Subbly::api('subbly.order')->update($order);
     *
     *     Subbly::api('subbly.order')->update($id, array(
     *         'firstname' => 'Jon',
     *         'lastname'  => 'Snow',
     *     ));
     *
     * @return \Subbly\Model\Order
     *
     * @api
     */
    public function update()
    {
        $args    = func_get_args();
        $order = null;

        if (count($args) == 1 && $args[0] instanceof Order) {
            $order = $args[0];
        }
        else if (count($args) == 2 && !empty($args[0]) && is_array($args[1]))
        {
            $order = $this->find($args[0]);
            $order->fill($args[1]);
        }

        if ($order instanceof Order)
        {
            if ($this->fireEvent('updating', array($order)) === false) return false;

            $order->setCaller($this);
            $order->save();

            $this->fireEvent('updated', array($order));

            return $order;
        }

        throw new Exception(sprintf(Exception::CANT_UPDATE_MODEL,
            'Subbly\\Model\\Order',
            $this->name()
        ));
    }

    /**
     * Service name
     */
    public function name()
    {
        return 'subbly.order';
    }
}
