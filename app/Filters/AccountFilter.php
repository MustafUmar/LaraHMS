<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\QueryFilter;

class AccountFilter extends QueryFilter {

    protected $request;
    protected $searchColumns = ['personid', 'firstname', 'lastname', 'phone'];

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($this->request);
    }

    public function ftSearch($word)
    {
        $term = strtolower(preg_replace('/[+\-><\(\)~*\"]+/', '', $word));
        if(preg_match('/[@+]+/', $word)) {
            $this->builder->where('email', 'LIKE', $term.'%');
        } else {
            $columns = implode(',',$this->searchColumns);
            $this->builder->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)" , $term.'*');
        }
    }

    public function getreq()
    {
        return $this->request;
    }

}
