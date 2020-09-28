<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\QueryFilter;

class FileFilter extends QueryFilter {

    protected $request;
    protected $searchColumns = ['fileno'];

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($this->request);
    }

    public function ftSearch($word)
    {
        $term = strtolower(preg_replace('/[+\-><\(\)~*\@"]+/', '', $word));
        $columns = implode(',',$this->searchColumns);
        $this->builder->whereRaw("MATCH ({$columns}) AGAINST (? IN BOOLEAN MODE)" , $term.'*');
    }

    public function type($value)
    {
        $this->builder->where('type',$value);
    }

    public function getreq()
    {
        return $this->request;
    }

}
