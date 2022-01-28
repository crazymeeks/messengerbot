<?php

namespace App\Models\Repositories;

use App\Models\Api\DataInterface;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

abstract class BaseRepository
{

    /**
     * Location of overloaded data
     * @var array
     */
    protected $data = [];

    protected $whereStack = [];

    protected $whereInStack = [];

    protected $orderByStack = [];

    protected $limit = [];



    public function save(DataInterface $data)
    {
        return $data->save();
    }

    public function setDataTableOrder(string $column, string $direction)
    {
        $this->data['order'] = [
            $column,
            $direction,
        ];

        return $this;
    }

    public function getDataTableOrder()
    {
        return $this->data['order'];
    }

    public function setRequest(\Illuminate\Http\Request $request)
    {
        $this->data['request'] = $request;

        return $this;
    }

    public function getRequest()
    {
        return $this->data['request'];
    }

    public function paginate(int $per_page)
    {
        
        $request = $this->getRequest();

        $filters = $this->getFilters();

        $options = $this->getQueryOptions();

        $results = $this->model->find($filters, $options)->toArray();
        
        $total = count($results);

        $current_page = $request->has('page') ? $request->page : 1;
        
        $starting_point = ($current_page * $per_page) - $per_page;
        
        $array = array_slice($results, $starting_point, $per_page, true);

        $results = new Paginator($array, $total, $per_page, $current_page, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        return $results;
    }


    /**
     * Construct mongodb where query
     *
     * @param mixed $field Could be a callable
     * @param mixed $value
     * 
     * @return $this
     */
    public function where($field, $value = null)
    {

        if ($field instanceof \Closure) {
            call_user_func_array($field, array($this));
            return $this;
        }
        
        $where = [
            $field => [
                '$eq' => $value
            ]
        ];
        
        $this->whereStack = array_merge($this->whereStack, $where);

        return $this;
    }

    /**
     * Construct mongodb $in query
     *
     * @param string $field
     * @param array $values
     * 
     * @return $this
     */
    public function whereIn(string $field, array $values)
    {

        if ($field instanceof \Closure) {
            call_user_func_array($field, array($this));
            
            return $this;
        }

        $in = [
            $field => [
                '$in' => $values
            ]
        ];

        $this->whereInStack = array_merge($this->whereInStack, $in);

        return $this;
    }

    /**
     * Set order by query
     *
     * @param string|callable $field
     * @param string $value
     * 
     * @return $this
     */
    public function orderBy(string $field, $value = 'asc')
    {
        if ($field instanceof \Closure) {
            call_user_func_array($field, array($this));
            
            return $this;
        }

        $orderby = [
            'sort' => [
                $field => $value == 'asc' ? 1 : -1,
            ]
        ];

        $this->orderByStack = array_merge($this->orderByStack, $orderby);

        return $this;
    }

    /**
     * Construct limit query
     *
     * @param integer $limit
     * 
     * @return $this
     */
    public function limit(int $limit)
    {

        $this->limit = ['limit' => $limit];

        return $this;
    }

    /**
     * Get where query filters
     *
     * @return array
     */
    public function getFilters()
    {
        return array_merge($this->getWhere(), $this->getWhereIn());
    }
    
    /**
     * Get query options. This options is usually used in model's find() method.
     *
     * @return array
     */
    public function getQueryOptions()
    {
        $options = array_merge($this->getOrderBy(), $this->limit);

        return $options;
    }

    public function getWhereIn()
    {
        return $this->whereInStack;
    }

    public function getWhere(): array
    {
        return $this->whereStack;
    }

    public function getOrderBy()
    {
        return $this->orderByStack;
    }


    /**
     * Determine if has where query constructed
     *
     * @return boolean
     */
    public function hasWhere()
    {
        return count($this->whereStack) > 0;
    }

    /**
     * Determine if has $in query constructed
     *
     * @return boolean
     */
    public function hasWhereIn()
    {
        return count($this->whereInStack) > 0;
    }

    public function hasOrderBy()
    {
        return count($this->orderByStack) > 0;
    }

    public function __call($method, $args)
    {

        // Dynamically set and get data
        $result = strncasecmp($method, 'set', 3);

        if ($result == 0) {
            $key = mb_substr($method, 3);
            list($value) = $args;
            $this->data[$key] = $value;
            return $this;
        }

        $result = strncasecmp($method, 'get', 3);

        if ($result == 0) {
            $key = mb_substr($method, 3);
            return isset($this->data[$key]) ? $this->data[$key] : null;
        }
    }
}