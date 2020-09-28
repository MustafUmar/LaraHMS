<?php

namespace App\Http\Controllers\personnel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Filters\PersonnelFilter;
use App\User;

class PersonnelController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['showActivateForm','activate','accountNotVerified']);
        $this->middleware('isreset')->except(['showActivateForm','activate','accountNotVerified','accountInactive']);
    }

    public function index() {
        $role = Auth::user()->role;

        switch ($role) {
            case 'Admin':
                return view('admin.dashboard');
                break;
            case 'Reception':
                return view('reception.dashboard');
                break;
            case 'Doctor':
                return view('doctor.dashboard');
                break;
            case 'Account':
                return view('account.dashboard');
                break;
            case 'Lab':
                return view('lab.dashboard');
                break;
            case 'Nurse':
                return view('nurse.dashboard');
                break;
            default:
                return view('home');
        }
    }

    public function personnels(Request $request)
    {
        if(!$request->has('uinfo')) {
            return response()->json(['message' => 'Authorization error'], 400);
        }
        $uinfo = explode('.', decrypt($request->uinfo));
        $user = Auth::user();
        if($uinfo[0] != $user->hashid && $uinfo[1] != $user->role) {
            return response()->json(['message' => 'Authorization error'], 400);
        }

        // $query = User::select('id', 'firstname','othername','lastname','role','photo')->where('active', 1);
        $query = DB::table('personnels')
            ->select(DB::raw("personid, CONCAT(firstname,' ', IFNULL(CONCAT(othername,' '), ''),lastname) as fullname, role, photo, 'Offline' as status"))
            ->where('active', 1);
        // Log::info($request->user());
        switch ($user->role) {
            case 'Admin':
                return $query->whereIn('role', ['Admin','Doctor','Reception'])->get();
            break;
            case 'Doctor':
                return $query->where('role','Doctor')->get();
            break;
            case 'Reception':
                return $query->where('role','Doctor')->get();
            break;
            case 'Nurse':
                return $query->where('role','Doctor')->get();
            break;
            default:
                return null;
            break;
        }
    }

    public function list(Request $request, PersonnelFilter $filter)
    {
        // return $request->all();
        $check = collect($request->all())->every(function($value, $key) {
            return empty($value);
        });
        if(!$check) {
            //paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
            $search = $filter->apply(User::query());

            return $search->paginate(10);
        } else
            return response()->json(['message' => 'Nothing'], 200);

    }

    public function showActivateForm(Request $request)
    {
        if(!($request->has('h0p1l')))
            return view('404');
        if(Auth::check()) {
            if(Auth::user()->isreset) {
                Auth::logout();
            } else {
                return redirect('/hpl/dashboard');
            }
        }

        return view('admin.reset')->with(['token' => $request->h0p1l, 'email' => $request->email]);

    }

    public function activate(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $response = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function($user, $password) {
                $user->password = Hash::make($password);
                $user->isreset = 0;
                $user->active = 1;
                $user->save();
            }
        );

        if($response == Password::PASSWORD_RESET) {
            return redirect('/hpl/login');
        } else {
            $ecode = Str::random(8);
            $request->session()->put('ecode', $ecode);
            return redirect()->action('personnel\PersonnelController@accountNotVerified', ['ec' => $ecode]);
        }

    }

    public function verifyAccess(Request $request)
    {
        // return $request->user();
        if(!Auth::check())
            return response()->json(['message' => 'Unauthorized'], 401);
        $authtoken = [
            'token' => $request->user()->createToken('apiToken')->accessToken,
            'expire' => intval(43200),
            'usertype' => encrypt(Auth::user()->hashid.'.'.Auth::user()->role),
            'user' => Auth::user()->personid
        ];

        return response()->json($authtoken, 200);
    }

    public function verifyUser(Request $request)
    {
        if(!Auth::check())
            return response()->json(['message' => 'Unauthorized'], 401);
        try {
            $webuser = decrypt($request['client']);
            $uid = explode('.', $webuser);
            if($request->user()->role != $uid[1] && $request->user()->hashid != $uid[0])
                return response()->json('Invalid client', 400);
        } catch (DecryptException $e) {
            return response()->json('Invalid', 403);
        }

        return response()->json($request->user()->personid, 200);
    }

    public function resetAccount(Request $request, $id)
    {
        $user = User::find(Hashids::decode($id))->first();
        if($user) {
            $user->isreset = 1;
            $user->active = 0;
            $user->save();
            Password::broker('vrfy')->sendResetLink(['email' => $user->email]);
            return response()->json(['message' => 'Success'], 200);
        } else
            return response()->json(['message' => 'No such account'], 400);
    }

    public function accountInactive()
    {
        if(Auth::user()->isreset)
            return view('admin.verificationrequest');
        else
            return redirect('/hpl/dashboard');
    }

    public function accountNotVerified(Request $request, $ecode)
    {
        if(Auth::check())
            return redirect('/hpl/dashboard');
        else if($request->session()->has('ecode') && $request->session()->get('ecode') == $ecode) {
            return view('admin.notverified');
        } else
            return view('404');

    }

}
