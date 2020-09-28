<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class QueryFilter {

    protected $request;
    protected $model;
    protected $builder;

    protected $reqmap = [
        'search' => 'ftSearch',
        'q' => 'search',
        'type' => 'doctype',
        'active' => 'active',
        'role' => 'role',
        'roles' => 'roles',
        'ftype' => 'type',
        'stat' => 'stat',
        'date' => 'date',
        'sort' => 'sort'
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    // public function ftSearch($term)
    // {
    //     $columns = implode(',',$this->model->getSearchColumns());
    //     $this->builder->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)" , $term);
    // }

    public function apply($builder)
    {
        // $this->model = $model;
        // $this->builder = $model->newQuery();

        $this->builder = $builder;

        foreach($this->filterMethods() as $name) {
            // Log::debug($this->request[$name]);
            if(!method_exists($this, $this->reqmap[$name])) {
                continue;
            }

            $this->{$this->reqmap[$name]}($this->request[$name]);

        }

        // for ($i=0; $i < count($filter); $i++) {
        //     $this->{$this->reqmap[$filter[$i]]}($this->request[$filter[$i]]);
        // }

        return $this->builder;

    }

    public function filterMethods()
    {
        $reqparams = array_keys($this->request->all());
        return array_keys(
            array_filter($this->reqmap, function($val, $key) use($reqparams) {
                return in_array($key, $reqparams);
            }, ARRAY_FILTER_USE_BOTH)
        );

    }

}
