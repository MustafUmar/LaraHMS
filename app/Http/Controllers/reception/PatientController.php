<?php

namespace App\Http\Controllers\reception;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;
use Carbon\Carbon;
use App\PatientFile;
use App\PatientRecord;
use App\PatientService;
use App\Service;
use App\Patient;
use App\Filters\PatientFilter;
use App\Filters\FileFilter;
use App\PatientSession;
use App\Payment;

class PatientController extends Controller
{
    public function patients(Request $request, PatientFilter $filter, FileFilter $file)
    {
        $patients = $filter->apply(Patient::query());
        $file = $file->apply(PatientFile::query());
        // $file = $request->has('search') ? PatientFile::where('fileno', 'LIKE', $request['search'])->take(10)->get() : null;
        $result = [
            'patients' => $patients->take(10)->get(),
            'files' => $file->take(10)->get()
        ];

        return $result;
    }

    public function patient($id)
    {
        $patient = Patient::find(Hashids::decode($id))->first()->load(['file','patrecord' => function($q) {
            $q->latest()->take(2)->get()->load(['patdoctor' => function($r) {
                $r->latest()->take(2)->get()->load('doctor');
            }]);
        }]);
        $serv = DB::table('patient_records')
                    ->join('payments','patient_records.id','=','payments.patrec_id')
                    ->join('patient_services','patient_records.id','=','patient_services.patrec_id')
                    ->join('services', 'patient_services.service_id', '=', 'services.id')
                    ->where('patient_records.patid', $patient->id)
                    ->whereIn('payments.status', ['Pending','Incomplete'])
                    ->select('patient_records.patid', 'services.name', 'services.charge', 'patient_services.status', 'payments.amount')
                    ->take(5)
                    ->get();

        $presc = DB::table('patient_records')
                    ->join('patient_prescriptions', 'patient_records.id', '=', 'patient_prescriptions.patrec_id')
                    ->where('patient_records.patid', $patient->id)
                    ->whereIn('patient_prescriptions.status', ['Pending','Incomplete'])
                    ->select('patient_records.patid', 'patient_prescriptions.status', 'patient_prescriptions.created_at')
                    ->take(5)
                    ->get();

        $outstand = [
            'service' => $serv,
            'prescribed' => $presc
        ];
        $patient['outstand'] = $outstand;

        return $patient;
    }

    public function file($id)
    {
        $patfile = PatientFile::find(Hashids::decode($id))->first()->load(['patients.patrecord' => function($q) {
            $q->latest()->first();
        }]);

        $serv = DB::table('patient_records')
            ->join('payments','patient_records.id','=','payments.patrec_id')
            ->join('patient_services', 'payments.id', '=', 'patient_services.payment_id')
            ->join('services', 'patient_services.service_id', '=', 'services.id')
            ->whereIn('patient_records.patid', $patfile->patients->pluck('id'))
            ->whereIn('payments.status', ['Pending','Incomplete'])
            ->select('patient_records.patid', 'services.name', 'services.charge', 'patient_services.status', 'payments.amount')
            ->take(5)
            ->get();

        $presc = DB::table('patient_records')
            ->join('patient_prescriptions', 'patient_records.id', '=', 'patient_prescriptions.patrec_id')
            ->whereIn('patient_records.patid', $patfile->patients->pluck('id'))
            ->whereIn('patient_prescriptions.status', ['Pending','Incomplete'])
            ->select('patient_records.patid', 'patient_prescriptions.status', 'patient_prescriptions.updated_at')
            ->take(5)
            ->get();

        $outstand = [
            'service' => $serv,
            'prescribed' => $presc
        ];

        $patfile['outstand'] = $outstand;

        return $patfile;
    }

    public function create(Request $request)
    {
        $request->validate([
            'filetype' => 'required|in:Individual,Family',
            'main' => 'required',
            'main.firstname' => 'required',
            'main.lastname' => 'required',
            'main.gender' => 'required|in:M,F',
            'main.dob' => 'required|date_format:Y-m-d',
            'main.email' => 'nullable|unique:patients,email',
            'main.phone' => 'required',
            'members.*.firstname' => 'required',
            'members.*.lastname' => 'required',
            'members.*.gender' => 'required|in:M,F',
            'members.*.dob' => 'required|date_format:Y-m-d',
            'members.*.email' => 'nullable|unique:patients,email',
        ]);

        try {
            $patid = $this->generateID();
            if($patid <= 0)
                throw new Exception('Unable to generate id');

            $main = [
                'patno' => $patid,
                'firstname' => $request['main']['firstname'],
                'othername' => $request['main']['othername'],
                'lastname' => $request['main']['lastname'],
                'gender' => $request['main']['gender'],
                'dob' => Carbon::createFromFormat('Y-m-d', $request['main']['dob']),
                'email' => $request['main']['email'],
                'phone' => $request['main']['phone'],
                'main' => 1
            ];

            $patients = [];
            // $patients[0] = $main;

            if($request->filetype == 'Family') {
                for ($i=0; $i < count($request->members); $i++) {
                    $mid = $this->generateID();
                    if($mid <= 0) {
                        throw new Exception('Unable to generate id');
                        break;
                    }
                    $member = [];
                    $member += ['patno' => $mid];
                    $member += ['firstname' => $request->members[$i]['firstname']];
                    $member += ['othername' => $request->members[$i]['othername']];
                    $member += ['lastname' => $request->members[$i]['lastname']];
                    $member += ['gender' => $request->members[$i]['gender']];
                    $member += ['dob' => Carbon::createFromFormat('Y-m-d', $request->members[$i]['dob'])];
                    $member += ['email' => $request->members[$i]['email']];
                    $member += ['phone' => $request->members[$i]['phone']];
                    $patients[$i] = $member;
                }
            }

            $file = null;
            DB::transaction(function() use($request, $patients, $main, &$file) {
                $seq = DB::select('select NEXT VALUE FOR patfile_seq as nextseq')[0]->nextseq;
                if(empty($seq))
                    throw new Exception('Unable to retrieve file sequence genertor');

                $file = PatientFile::create([
                    'fileno' => str_pad($seq, 9, '0', STR_PAD_LEFT),
                    'type' => $request->filetype
                ]);

                $mainpat = $file->patients()->create($main);

                $file->patients()->createMany($patients);

                $patrec = $mainpat->patrecord()->create();

                $service = Service::where('slug', 'patfile')->first();
                if(!empty($service )) {
                    if(floatval($service->charge) > 0) {
                        // $pay = $patrec->payment()->create(['status' => 'Pending']);
                        $pay = new Payment;
                        $pay->patient()->associate($mainpat);
                        $pay->patrecord()->associate($patrec);
                        $pay->status = 'Pending';
                        $pay->save();

                        $patservice = new PatientService;
                        $patservice->services()->associate($service);
                        $patservice->patrecord()->associate($patrec);
                        $patservice->payment()->associate($pay);
                        $patservice->service_name = $service->name;
                        $patservice->status = 'Pending';
                        $patservice->mode = 'file:'.$file->id;
                        $patservice->save();

                        $file->pmod = $patservice->id;
                        $file->save();

                    }
                }

            });
        } catch (Exception $e) {
            error_log($e->getMessage());
            return response()->json(['message' => 'Unable to create patient file.'], 400);
        }

        return response()->json(['message' => 'Success', 'file' => $file], 200);
    }

    private function generateID() {
        $pid = 0;
        $check = [];
        for ($i=0; $i < 6; $i++) {
            for ($j=0; $j < 4; $j++) {
                $check[$j] = mt_rand(10000000, 99999999);
            }

            $q = Patient::whereIn('patno', $check)->pluck('patno')->toArray();
            $comp = array_diff($check, $q);
            if(count($comp)) {
                $pid = $comp[0];
                break;
            }
            $check = [];
        }
        return $pid;
    }
}
