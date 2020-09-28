<?php

namespace App\Http\Controllers\personnel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\User;
use Exception;

class ReceptionController extends Controller
{

    public function index()
    {
        $recep = User::where('role','Reception')->get();
        return $recep;
    }

    public function create(Request $request)
    {
        $this->recepValidator($request)->validate();

        $data = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'othername' => $request->othername,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => 'Reception',
            'job_title' => 'Receptionist'
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

            $data['personid'] = 'SP'.$pid;

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
            return response()->json(['message' => 'Unable to create the receptionist.'], 400);
        }

        return response()->json(['message' => 'Success'], 200);
    }

    public function update(Request $request, $id)
    {
        $this->recepValidator($request)->validate();

        $data = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'othername' => $request->othername,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        $user = User::find($id);
        if(!$user)
            abort('No such receptionist in records');

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
            return response()->json(['message' => 'Unable to update staff\'s account.'], 400);
        }

        return response()->json(['message' => 'Success'], 200);
    }

    public function view($id)
    {
        $rep = User::find(Hashids::decode($id))->first();
        if(!$rep && $rep->role != 'Reception')
            abort(404, 'Unable to find staff');
        return $rep;
    }

    private function recepValidator($request)
    {
        $data = [
            'firstname' => 'required|max:100',
            'othername' => 'max:100',
            'lastname' => 'required|max:100',
            'email' => 'required|email|unique:personnels',
            'phone' => 'required|unique:personnels|max:50',
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
            if(!(User::where('personid', 'SP'.$pid)->exists())) {
               break;
            }
        }
        return $pid;
    }
}
