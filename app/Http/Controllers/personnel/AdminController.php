<?php

namespace App\Http\Controllers\personnel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Cookie;

class AdminController extends Controller
{

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/hpl/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    protected function credentials(Request $request) {
        return array_merge($request->only($this->username(), 'password'), ['active' => 1]);
    }

    protected function authenticated(Request $request, $user)
    {
        // Cookie::queue('apitoken', $user->createToken('apiToken')->accessToken, 30);
        // Cookie::queue('txexp', '1800', 30);
    }

    protected function loggedOut(Request $request)
    {
        return redirect('hpl/login');
    }
}
