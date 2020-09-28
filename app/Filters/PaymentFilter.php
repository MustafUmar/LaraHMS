<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\QueryFilter;
use Carbon\Carbon;
use Exception;

class PaymentFilter extends QueryFilter {

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($this->request);
    }

    public function stat($value)
    {
        switch ($value) {
            case 'Pending':
                $this->builder->where('status', $value);
            break;
            case 'Incomplete':
                $this->builder->where('status', $value);
            break;
            case 'Complete':
                $this->builder->where('status', $value);
            break;
        }

    }

    public function date($value)
    {
        switch ($value) {
            case 'today':
                $today = Carbon::now();
                // $this->builder->whereBetween('payment_date', ['2020-03-03 00:00', '2020-0-06 00:00']);
                $this->builder->whereBetween('payments.created_at', [$today->startOfDay()->format('Y-m-d H:i:s'), $today->endOfDay()->format('Y-m-d H:i:s')]);
            break;
            case 'yest':
                $yest = Carbon::now()->subDay();
                $this->builder->whereBetween('payments.created_at',
                    [$yest->startOfDay()->format('Y-m-d H:i:s'), $yest->endOfDay()->format('Y-m-d H:i:s')]
                );
            break;
            case 'on':
                try {
                    $custom = Carbon::createFromFormat('Y-m-d', $this->request['on_date']);
                    $this->builder->whereBetween('payments.created_at',
                        [$custom->startOfDay()->format('Y-m-d H:i:s'), $custom->endOfDay()->format('Y-m-d H:i:s')]
                    );
                } catch (Exception $e) {
                    error_log('Invalid date for filter');
                }
            break;
            case 'from':
                try {
                    $custom1 = Carbon::createFromFormat('Y-m-d', $this->request['from_dt1']);
                    $custom2 = Carbon::createFromFormat('Y-m-d', $this->request['from_dt2']);
                    $this->builder->whereBetween('payments.created_at',
                        [$custom1->startOfDay()->format('Y-m-d H:i:s'), $custom2->endOfDay()->format('Y-m-d H:i:s')]
                    );
                } catch (Exception $e) {
                    error_log('Invalid date for filter');
                }
            break;
            default:
                # code...
            break;
        }

    }

    public function sort($value)
    {
        switch ($value) {
            case 'name':
                $this->builder->orderBy('patients.firstname', intval($this->request['order']) == 1 ? 'desc' : 'asc');
            break;
            case 'date':
                $this->builder->orderBy('payments.updated_at', 'desc');
            break;
            default:
                $this->builder->orderBy('payments.updated_at', 'desc');
            break;
        }
    }
}
