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
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\Filters\AccountFilter;
use App\User;

class AccountController extends Controller
{
    public function accounts(Request $request, AccountFilter $filter)
    {
        $accounts = $filter->apply(User::where('role','Account'));
        return $accounts->get();
    }

    public function view($id)
    {
        $doctor = User::find(Hashids::decode($id)[0]);
        if($doctor && $doctor->role != 'Account')
            abort(404, 'Unable to find accountant');
        return $doctor;
    }

    public function create(Request $request)
    {
        $this->accValidator($request)->validate();

        $data = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'othername' => $request->othername,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => 'Account',
            'job_title' => 'Accountant'
        ];

        if(boolval($request->account)) {
            $data['isreset'] = 1;
            $data['active'] = 0;
        }

        $filename = null;
        try {
            $pid = $this->generateID();
            if($pid <= 0)
                throw new Exception('Unable to generate id');

            $data['personid'] = 'AC'.$pid;

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
        $this->accValidator($request)->validate();

        $data = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'othername' => $request->othername,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        $user = User::find($id);
        if(!$user)
            abort('No such accountant in records');

        if(boolval($request->account) && !$user->active) {
            $data['isreset'] = 1;
            $data['active'] = 0;
        } else if(!boolval($request->account) && $user->active) {
            $data['password'] = null;
            $data['isreset'] = 0;
            $data['active'] = 0;
        }

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

            DB::transaction(function () use($data, $user, $request) {
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
            return response()->json(['message' => 'Unable to update accountant.'], 400);
        }

        return response()->json(['message' => 'Success'], 200);
    }

    private function accValidator($request)
    {
        $data = [
            'firstname' => 'required|max:200',
            'othername' => 'max:200',
            'lastname' => 'required|max:200',
            'email' => 'required|email',
            'phone' => 'required|max:200',
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
            if(!(User::where('personid', 'AC'.$pid)->exists())) {
               break;
            }
            $pid = 0;
        }
        return $pid;
    }
}
