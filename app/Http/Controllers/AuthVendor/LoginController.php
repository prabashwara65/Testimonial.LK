<?php

namespace App\Http\Controllers\AuthVendor;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'vendor';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:vendor')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('authVendor.login');
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if (filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            return ['email' => $request->email, 'password' => $request->password, 'status' => 1, 'branch_status' => 1, 'company_status' => 1];
        }
        return ['username' => $request->email, 'password' => $request->password, 'status' => 1, 'branch_status' => 1, 'company_status' => 1];
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    public function guard()
    {
        return Auth::guard('vendor');
    }

    protected function authenticated(Request $request, $user)
    {
    if ($user->hasRole('Representative')) {
        return redirect()->route('vendor.salesrep.dashboard');
    }

    return redirect()->route('vendor.dashboard');
    }
}
