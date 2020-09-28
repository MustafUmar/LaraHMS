<?php

namespace App\Http\Controllers\account;

use App\Filters\PatientFilter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Log;
use App\Patient;
use App\Payment;

class PatientController extends Controller
{
    public function index()
    {
        // $recent = PatientRecord::with('patient')->whereHas('payment', function($q) {
        //     $q->latest()->first();
        // })->oldest()->take(10);
        // $recent = DB::table('patient_records')->join('patients','patient_records.patid', '=', 'patients.id')
        //     ->join('payments', 'patient_records.id', '=', 'payments.patrec_id')
        //     ->orderBy('payments.status', 'desc')->orderBy('patient_records.updated_at', 'desc')
        //     ->select('patient_records.id', 'patients.patno', 'patients.firstname', 'patients.othername' ,'patients.lastname','payments.status','patient_records.updated_at')
        //     ->take(10)
        //     ->get();

        // return $recent;

        return Payment::with('patient')->orderBy('created_at')->take(10)->get();
    }

    public function search(Request $request, PatientFilter $filter)
    {
        // $query = Patient::with(['patrecord' => function($q) {
        //     $q->with('payment')->latest()->take(1)->get();
        // }]);
        // $patients = $filter->apply($query);
        $patsub = DB::table('patient_records')->leftJoin('payments', 'patient_records.id', '=', 'payments.patrec_id')
                    ->orderBy('patient_records.updated_at')
                    ->select('patient_records.patid', 'patient_records.updated_at', 'payments.status');
        $query = Patient::leftJoinSub($patsub, 'patrecords', function($j) {
            $j->on('patients.id', '=', 'patrecords.patid');
        })->select('id', 'firstname', 'lastname', 'patno', 'dob', 'patrecords.updated_at', 'patrecords.status');
        $patients = $filter->apply($query);

        $patients->take(15);

        return $patients->get();

    }

    public function patient($id)
    {
        $patient = Patient::find(Hashids::decode($id))->first();
        $patient->load(['payment.patrecord' => function($q) {
            $q
            ->orderBy('status', 'desc')->latest()
            ->take(20)->get();
        }]);
        // Payment::where('patid', $patient->id)
        return $patient;
    }
}
