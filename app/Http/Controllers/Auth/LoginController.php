<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
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

    use AuthenticatesUsers, ThrottlesLogins;

    protected $maxAttempts = 3; // Default is 5
    protected $decayMinutes = 1; // Default is 1
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }

    public function login(Request $request)
    {
        $this->validateLogin($request);


        $auth = [
            'email' => $request->email,
            'password' => $request->password,
            'active' => User::ACTIVE
        ];
        $remember = $request->remember = 'on';
        if (Auth::attempt($auth, $remember)) {
            $request->session()->regenerate();
            $user = Auth::guard()->user();
            // Create log
            // $event = 'Login';
            // $this->createLog($event, $user);
            return redirect()->route('home');
        } else {
            if ($this->hasTooManyLoginAttempts($request)) {

                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }
            $this->incrementLoginAttempts($request);
            return $this->sendFailedLoginResponse($request);
        }
    }

    protected function validateLogin(Request $request)
    {
        $request->validate(
            [
                $this->username() => 'required',
                'password' => 'required',
            ],
            [
                'email.required' => 'Email không được để trống!',
                'password.required' => 'Mật khẩu không được để trống!'
            ]
        );
    }
}
