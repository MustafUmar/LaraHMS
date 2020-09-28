<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\QueryFilter;

class StaffFilter extends QueryFilter {

    protected $request;
    protected $searchColumns = ['personid', 'firstname', 'lastname', 'phone'];

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($this->request);
    }

    public function ftSearch($term)
    {
        $columns = implode(',',$this->searchColumns);
        $this->builder->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)" , $term.'*');
    }

}
