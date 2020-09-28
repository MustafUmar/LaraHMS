<?php

namespace App\Http\Controllers\account;

use App\Filters\PaymentFilter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;
use App\Extension\Mediator;
use App\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payment = DB::table('payments')->select(DB::raw('status, count(status) as count'))->groupBy('status')->get();
        $recent = Payment::with('patient')->orderBy('created_at')->take(10)->get();
        Log::debug($payment);
        $paystat = null;
        // $payment->each(function($v, $k) use(&$paystat) {
        //     $paystat[$v->status] = $v->count;
        // });
        foreach ($payment as $v) {
            $paystat[$v->status] = $v->count;
        }
        // $paystat['Pending'] = $payment->firstWhere('status', 'Pending')['count'];
        // $paystat['Incomplete'] = $payment->firstWhere('status', 'Incomplete')['count'];
        // $paystat['Complete'] = $payment->firstWhere('status', 'Complete')['count'];

        return ['paystat' => $paystat, 'recent' => $recent];

    }

    public function payments(Request $request, PaymentFilter $filter)
    {
        $request->validate([
            'stat' => 'required|in:All,Pending,Incomplete,Complete',
            'date' => 'required|in:today,yest,on,from,all',
            'on_date' => 'nullable|date_format:Y-m-d|before:tomorrow',
            'from_dt1' => 'nullable|date_format:Y-m-d|different:from_dt2|before:tomorrow',
            'from_dt2' => 'nullable|date_format:Y-m-d|different:from_dt1|before:tomorrow',
            'sort' => 'nullable|in:date,name',
            'order' => 'nullable|in:0,1'
        ]);
        // return $request->all();
        $query = Payment::join('patients', 'payments.patid', '=', 'patients.id')
                    ->select('payments.id','patients.firstname', 'patients.lastname', 'payments.status', 'payments.created_at');
        $payments = $filter->apply($query);

        return $payments->get();
    }

    public function payment($id)
    {
        $pay = Payment::find(Hashids::decode($id)[0]);
        $pay->load(['patient.file', 'patpresc.meds.pharm', 'patservice.services']);

        return $pay;
    }

    public function charge(Request $request)
    {
        $v = Validator::make($request->all(), [
                'pid' => 'required',
                'amount' => 'required|numeric|min:5|max:10000000',
                'type' => 'required|in:cash,credit_card'
            ],
            [
                'pid.required' => 'Payment reference not found.',
                'type.required' => 'Please choose a payment method.'
            ]
        );
        $v->validate();

        try {
            $pay = Payment::find(Hashids::decode($request->pid)[0]);
            if(empty($pay))
                return response()->json(['message' => 'No such payment informatiom.'], 400);

            if($pay->amount != 'Complete') {

                $pay->load(['patpresc.meds.pharm', 'patservice.services']);
                $amount = floatval($request->amount);
                $services = $pay->patservice;
                $prescription = $pay->patpresc;
                $totalService = $this->serviceTotal($services);
                $totalPrescription = $this->medsTotal($prescription);
                $subTotal = floatval($totalService) + floatval($totalPrescription);
                $tax = 0.075 * $subTotal;
                $additional = $tax;
                $grandTotal = $subTotal + $additional;
                $totalPaid = $this->paidAmount($services, $prescription);
                $balance = $grandTotal - $totalPaid;
                $residual = $amount - $balance;

                $v->after(function($v) use($additional, $balance, $amount, $residual) {
                    // if($amount < $grandTotal)
                    //     $v->errors()->add('amount', 'Amount is less the total');
                    // if($amount > (ceil($grandTotal/1000)*1000))
                    //     $v->errors()->add('amount', 'Overcharged amount.');
                    // else if($grandTotal >= 2000 && $amount < 2000) {
                    //     $v->errors()->add('amount', 'Only complete payment is accepted for payments below 2000.');
                    // }
                    if($amount > (ceil($balance/1000)*1000))
                        $v->errors()->add('amount', 'Overcharged amount.');
                    // else if($balance <= 2000 && $amount < $balance) {
                    //     $v->errors()->add('amount', 'Only complete payment is accepted for payments below 2000.');
                    // }
                    else if($additional > 0 && $residual < 0) {
                        if((abs($residual) - $additional) <= 0)
                            $v->errors()->add('amount', 'Cannot accept part payment for additional charges.');
                    }
                });
                $v->validate();

                $midmodel = [];
                foreach ($services as $sv) {
                    if(floatval($sv->paid) != floatval($sv->services->charge)) {
                        $rem = floatval($sv->services->charge) - floatval($sv->paid);

                        if($amount >= $rem) {
                            $sv->paid = $rem + floatval($sv->paid);
                            $amount = $amount - $rem;
                            $sv->status = 'Complete';
                            if(!empty($sv->mode)) {
                                $md = Mediator::mitigate($sv->mode);
                                !empty($md) ? array_push($midmodel, $md) : null;
                            }
                        }
                        else if($amount > 0) {
                            $sv->paid = $amount;
                            $sv->status = 'Incomplete';
                            $amount = 0;
                            break;
                        }
                    }
                }

                if($amount > 0) {
                    //change prescription status
                    $prescstat = 'Pending';
                    foreach($prescription as $presc) {
                        if($amount > 0) {
                            foreach($presc as $meds) {
                                $ms = $meds->unit ? 'price_per_'+$meds->unit : 'price';
                                if(floatval($meds->pharm->{$ms}) != floatval($meds->amount)) {
                                    $rem = floatval($meds->pharm->{$ms}) - floatval($meds->amount);
                                    if($amount >= $rem) {
                                        $meds->amount = $rem + $meds->amount;
                                        $amount = $amount - $rem;
                                        $meds->status = 'Complete';
                                        $prescstat = 'Complete';
                                    } else if ($amount > 0) {
                                        $meds->amount = $amount;
                                        $meds->status = 'Incomplete';
                                        $prescstat = 'Incomplete';
                                        $amount = 0;
                                        break 2;
                                    }
                                }
                                // switch ($meds->unit) {
                                //     case 'sat':
                                //         if(floatval($meds->pharm->price_per_sat) != floatval($meds->amount)) {
                                //             $rem = floatval($meds->pharm->price_per_sat) - floatval($meds->amount);
                                //             if($amount > $rem) {
                                //                 $meds->amount = $rem + $meds->amount;
                                //                 $amount = $amount - $rem;
                                //             } else if ($amount > 0) {
                                //                 $meds->amount = amount;
                                //                 $amount = 0;
                                //                 break 3;
                                //             }
                                //         }
                                //     break;

                                //     default:
                                //         # code...
                                //         break;
                                // }
                            }

                            $presc->status = $prescstat;

                        } else break;
                    }
                }

                //additional charges
                // if($amount > 0) {

                // }

                $totalPaid = $this->paidAmount($services, $prescription);

                Log::debug([
                    // 'service' => $services,
                    // 'prescription' => $prescription,
                    'totalService' => $totalService,
                    'totalPresc' => $totalPrescription,
                    'subtotal' => $subTotal,
                    'grandTotal' => $grandTotal,
                    'paid' => $totalPaid
                ]);

                if($totalPaid > 0) {
                    //total prescription and services paid
                    $pay->amount = $totalPaid;
                    //total amount given by the patient to pay for the prescription and services
                    $pay->paid = floatval($pay->paid) + floatval($request->amount);
                    $pay->rem = $pay->paid > $pay->amount ? abs($pay->paid - $pay->amount) : 0;
                    $pay->status = $totalPaid >= $grandTotal ? 'Complete' : $totalPaid == 0 ? 'Pending': 'Incomplete';
                    $pay->type = $request->type;
                    $pay->payment_date = now();

                    // DB::transaction(function () use($pay, $midmodel) {

                    //     $pay->push();
                    //     if(count($midmodel) > 0) {
                    //         foreach ($midmodel as $v) {
                    //             $v->save();
                    //         }
                    //     }
                    // });

                }

                return ['pay' => $pay, 'mediator' => $midmodel];



            } else
                return response()->json(['message' => 'No such account.'], 400);

        } catch (Exception $ex) {
            Log::debug($ex->getMessage());
            return response()->json(['message' => $ex->getMessage()], 400);
        }

    }

    public function validatorMessage()
    {
        return [
            'pid.required' => 'Missing reference.',
            'amount.required' => 'The amount is required.'
        ];
    }

    public function serviceTotal($services)
    {
        return $services->reduce(function($ac, $item) {
            return $ac + floatval($item->services->charge);
        }, 0.0);
    }

    public function medsTotal($prescriptions, $type = 0)
    {
        $total = 0;
        foreach ($prescriptions as $meds) {
            if($type == 0) {
                $total += $meds->reduce(function($ac, $item) use($type) {
                    switch ($item->unit) {
                        case 'sat':
                            return $ac + (intval($item->qty) * floatval($item->pharm->price_per_sat));
                            break;
                        case 'gram':
                            return $ac + (intval($item->qty) * floatval($item->pharm->price_per_gram));
                            break;
                        case 'pill':
                            return $ac + (intval($item->qty) * floatval($item->pharm->price_per_pill));
                            break;
                        case 'tab':
                            return $ac + (intval($item->qty) * floatval($item->pharm->price_per_tab));
                            break;
                        case 'pack':
                            return $ac + (intval($item->qty) * floatval($item->pharm->price_per_pack));
                            break;
                        case 'ml':
                            return $ac + (intval($item->qty) * floatval($item->pharm->price_per_ml));
                            break;
                        case 'shot':
                            return $ac + (intval($item->qty) * floatval($item->pharm->price_per_shot));
                            break;
                        default:
                            return $ac + 0;
                            break;
                    }
                }, 0);
            } else {
                $total += $meds->reduce(function($ac, $item) {
                    return $ac + floatval($item->amount);
                }, 0.0);
            }

        }

        return $total;

    }

    public function paidAmount($service, $prescription)
    {
        $total = 0;
        $total += $service->reduce(function($ac, $item) {
            return $ac + floatval($item->paid);
        }, 0.0);

        $total += $prescription->reduce(function($acc, $meds) {
            return $acc + $meds->reduce(function($ac,$item) {
                return $ac + floatval($item->amount);
            }, 0.0);
        }, 0.0);

        return $total;
    }
}
