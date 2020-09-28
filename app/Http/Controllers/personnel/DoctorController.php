<?php

namespace App\Http\Controllers\personnel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\Skill;
use App\User;
use App\Filters\DoctorFilter;
use Exception;

class DoctorController extends Controller
{
    public function doctors(Request $request, DoctorFilter $filter)
    {
        // return User::where('role','Doctor')->get();
        $doctors = $filter->apply(User::where('role','Doctor')->with('skills'));
        return $doctors->get();
    }

    public function filteredDoctor(Request $request, DoctorFilter $filter)
    {
        // var_dump($request->query());
        // error_log('Reqs '.$request->all());
        $doctors = $filter->apply(User::where('role','Doctor'));
        // return $request->all();
        return $doctors->get();
    }

    public function create(Request $request)
    {
        $this->docValidator($request)->validate();

        $data = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'othername' => $request->othername,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => 'Doctor',
            'type' => $request->type,
        ];

        if(boolval($request->account)) {
            // $data['password'] = Hash::make('12345');
            $data['isreset'] = 1;
            $data['active'] = 0;
        }

        $filename = null;
        try {
            $pid = $this->generateID();
            if($pid <= 0)
                throw new Exception('Unable to generate id');

            $data['personid'] = 'DT'.$pid;

            if($request->hasFile('photo')) {
                $photo = Image::make($request->photo);
                $filename = Carbon::now()->timestamp.'_'.uniqid().'.'.$request->photo->getClientOriginalExtension();
                $photo->resize(
                    $photo->width() < 500 ? $photo->width() : 500, $photo->height() < 500 ?$photo->height() : 500,
                    function ($constraint) { $constraint->aspectRatio(); }
                );
                Storage::put('public/personnel/'.$filename, $photo->stream());
                $data['photo'] = $filename;
            }

            DB::transaction(function () use($data, $request) {
                $user = User::create($data);
                $user->skills()->attach(Hashids::decode($request->expertise));

                if(boolval($request->account))
                    Password::broker('vrfy')->sendResetLink(['email' => $request->email]);
            });

        } catch (Exception $e) {
            if($filename)
                Storage::delete('public/personnel/'.$filename);
            error_log($e->getMessage());
            return response()->json(['message' => 'Unable to create doctor account.'], 400);
        }

        return response()->json(['message' => 'Success'], 200);

    }

    public function update(Request $request, $id)
    {
        $this->docValidator($request)->validate();

        $data = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'othername' => $request->othername,
            'email' => $request->email,
            'phone' => $request->phone,
            'type' => $request->type,
        ];

        $user = User::find($id)->load('skills');
        if(!$user)
            abort('No such doctor in records');

        if(boolval($request->account) && !$user->active) {
            $data['isreset'] = 1;
            $data['active'] = 0;
        } else if(!boolval($request->account) && $user->active) {
            $data['password'] = null;
            $data['isreset'] = 0;
            $data['active'] = 0;
        }

        $pids = $user->skills->pluck('id');
        $oldfile = null;
        $filename = null;
        try {
            if($request->hasFile('photo')) {
                if(isset($user->photo)) {
                    $oldfile = $user->photo;
                    Storage::move('public/personnel/'.$oldfile, 'temp/'.$oldfile);
                }

                $photo = Image::make($request->photo);
                $filename = Carbon::now()->timestamp.'_'.uniqid().'.'.$request->photo->getClientOriginalExtension();
                $photo->resize(
                    $photo->width() < 500 ? $photo->width() : 500, $photo->height() < 500 ?$photo->height() : 500,
                    function ($constraint) { $constraint->aspectRatio(); }
                );
                Storage::put('public/personnel/'.$filename, $photo->stream());
                $data['photo'] = $filename;
            }

            DB::transaction(function () use($data, $request, $user, $pids) {

                if($pids->count()) {
                    $user->skills()->detach($pids);
                }
                $user->skills()->attach(Hashids::decode($request->expertise));

                $user->update($data);

                if(boolval($request->account) && !$user->active)
                    Password::broker('vrfy')->sendResetLink(['email' => $user->email]);

            });

            if($oldfile)
                Storage::delete('temp/'.$oldfile);

        } catch (Exception $ex) {
            if($filename)
                Storage::delete('public/personnel/'.$filename);
             if($oldfile)
                Storage::move('temp/'.$oldfile, 'public/personnel/'.$oldfile);
            error_log($ex->getMessage());
            return response()->json(['message' => 'Unable to update doctor\'s account.'], 400);
        }

        return response()->json(['message' => 'Success'], 200);
    }

    public function view($id)
    {
        $doctor = User::find(Hashids::decode($id))->load('skills')->first();
        if($doctor && $doctor->role != 'Doctor')
            abort(404, 'Unable to find doctor');
        return $doctor;
    }

    public function skills()
    {
        return Skill::select('id','name')->get();
    }

    private function docValidator($request)
    {
        $data = [
            'firstname' => 'required|max:100',
            'othername' => 'max:100',
            'lastname' => 'required|max:100',
            'email' => 'required|email|unique:personnels',
            'phone' => 'required|unique:personnels|max:50',
            'type' => 'required|in:general,specialist',
            'expertise' => 'required',
            'account' => 'required|boolean',
        ];

        if($request->hasFile('photo'))
            $data['photo'] = 'image|mimes:jpeg,jpg,png,gif|max:2048';

        return Validator::make($request->all(), $data);
    }

    private function generateID() {
        $pid = 0;
        for ($i=0; $i < 60; $i++) {
            $pid = mt_rand(100000, 999999);
            if(!(User::where('personid', 'DT'.$pid)->exists())) {
               break;
            }
            $pid = 0;
        }
        return $pid;
    }
}
