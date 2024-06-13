<?php

namespace App\Http\Controllers\Auth;

use App\Bases\BaseModule;
use App\Libraries\AuthAPI;
use App\Libraries\Ldap;
use App\Libraries\Sso;
use App\Models\User;
use App\Modules\Admin\User\Model as UserModel;
use App\Modules\Admin\User\Service as ServiceUser;
use App\Modules\Admin\Role\Model as RoleModel;
use App\Modules\Admin\Role\Service as RoleService;
use App\Modules\Master\Unitkerja\Service as UnitkerjaService;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use DB;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\Repository as Cache;

class LoginController extends BaseModule
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
    protected $cache, $limitLogin;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Cache $cache)
    {
        $this->middleware('guest')->except('logout');
        $this->cache = $cache;
        $this->limitLogin = (int)config('data.login_max_attemp');
    }

    public function username()
    {
        return (config('data.login_auth_with')) ? config('data.login_auth_with') : 'username';
    }

    public function showLoginForm(Request $request)
    {
        return $this->serveView([], 'pages.auth.login');
    }

    protected function credentials(Request $request)
    {
        $request->merge(['status' => 1]);

        if (config('ldap.auth') == 'true') {
            return $request->only($this->username(), 'status');
        }

        return $request->only($this->username(), 'password', 'status');
    }

    public function authenticated(Request $request, $user)
    {

        activity('login')->withProperties($request->only($this->username()))->log('login berhasil');

        // clean cache
        $key = $this->throttleKey($request);
        $this->cache->forget($key);
        
        if (auth()->user()->kategori == 'frontend') {
            return redirect()->route('landing');
        } else {
            return redirect()->route('home');
        }
    }

    protected function validateLogin(Request $request)
    {

        if (config('data.login_with_captcha') == 'true') {

            $validator = Validator::make($request->all(), [
                $this->username() => 'required|string',
                'password' => 'required|string',
                'captcha'  => 'required|captcha'
            ]);

            if ($validator->fails()) {

                activity('login')->withProperties($request->only($this->username()))->log('login failed');

                $request->validate([
                    $this->username() => 'required|string',
                    'password' => 'required|string',
                    'captcha'  => 'required|captcha'
                ]);
            }
        } else {
            $validator = Validator::make($request->all(), [
                $this->username() => 'required|string',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {

                activity('login')->withProperties($request->only($this->username()))->log('login failed');

                $request->validate([
                    $this->username() => 'required|string',
                    'password' => 'required|string'
                ]);
            }
        }
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        /**
         * cek user blokir ip
         * */

        if ($this->checkUserBlokir($request)) {
            activity('login')->withProperties($request->only($this->username()))->log('user has bloked');
            return $this->sendBlokedLoginResponse($request);
        }

        if(config('data.single_device') == 'true'){
            if ($this->checkUserIsLogin($request->get($this->username()))) {
                return $this->sendIsLoginResponse($request);
            }
        }

        if ($this->limitAttemptLoginFailed($request)) {
            $this->setLockedUser($request);
            activity('login')->withProperties($request->only($this->username()))->log('login bloked');
            return $this->sendBlokedLoginResponse($request);
        }


        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            if (config('data.single_device') == 'true' && $request->get($this->username()) != 'admin') {
                User::where($this->username(), $request->get($this->username()))->update(['is_login' => true]);
            }

            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginFailed($request);

        activity('login')->withProperties($request->only($this->username()))->log(trans('auth.failed'));
        return $this->sendFailedLoginResponse($request);
    }

    protected function sendNotregisteredLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.unregistered')],
        ]);
    }

    public function setLockedUser($request)
    {
        $data = explode('|', $this->throttleKey($request));
        $attr = ['key' => $data[0], 'ip_address' => $data[1]];
        $this->cache->forget($this->throttleKey($request));

        (new ServiceUser)->setLockedUser($attr);
    }

    public function checkUserBlokir($request)
    {
        $data = explode('|', $this->throttleKey($request));
        $attr = ['key' => $data[0], 'ip_address' => $data[1]];
        return (new ServiceUser)->checkUserBlockedByIp($attr);
    }

    public function checkUserIsLogin($request)
    {
        $attr = ['key' => $request];
        return (new ServiceUser)->checkUserIsLogin($attr);
    }

    protected function sendBlokedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.blocked')],
        ]);
    }

    protected function sendIsLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('Maaf akun ini tengah login pada device lain, Harap coba kembali nanti!')],
        ]);
    }

    protected function sendErrorResponseAPI(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('Terdapat kendala pada koneksi LDAP')],
        ]);
    }

    public function limitAttemptLoginFailed($request)
    {
        return ((int)$this->cache->get($this->throttleKey($request)) == $this->limitLogin);
    }

    public function incrementLoginFailed($request)
    {
        $key = $this->throttleKey($request);
        if($this->cache->has($key)){
            $hits = (int) $this->cache->increment($key);
        }else{
            $hits = $this->cache->add($key, 0, now()->endOfDay());
        }
        return $hits;
    }

    public function loggedOut(Request $request)
    {
        activity('logout')
            ->withProperties(['username' => auth()->user()->username])
            ->log('logout');
    }

    public function logout(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }
        if (config('data.single_device') == 'true') {
            User::where('id', auth()->user()->id)->update(['is_login' => false]);
        }

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $this->cache->forget('otoritas_menus');
        $this->cache->forget('app_config');

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('../');
    }

    public function token(Request $request){
        $auth  = new AuthAPI();

        dd($auth->token());
    }

    public function loginwithldap(Request $request){
        $auth  = new AuthAPI();

        dd($auth->login('967355643', '123456'));
    }
}
