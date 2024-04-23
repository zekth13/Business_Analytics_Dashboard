<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


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
    protected $redirectTo = RouteServiceProvider::HOME;
    
    protected $pass_no;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->pass_no = 'pass_no';
    }

    /**
     * Get username property.
     *
     * @return string
     */
    public function username()
    {
        return $this->pass_no;
    }
    
    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $user = User::where("pass_no", $request->{$this->username()})
                ->select("password")
                ->first();
        
        if($user) {
            $hashed = $user->password;

            if (Hash::needsRehash($hashed)) {
                $hashed = Hash::make($request->password);

                $update = User::where("pass_no", $request->{$this->username()})
                        ->update(["password" => $hashed]);
            }
        }
        
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }
}
