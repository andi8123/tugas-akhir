<?php

namespace App\Http\Controllers;

use App\Models\AuthService;
use Illuminate\Http\Request;
use App\Models\LoginService;
use App\Models\UserService;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new AuthService();
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = LoginService::authenticate($request->email, $request->password);

        if ($result['success']) {
            return redirect()->route('dashboard.user')->with('success', 'Login successful!');
        } else {
            return back()->withErrors(['login_error' => $result['message']]);
        }
    }

    public function checkToken(Request $request)
    {
        if ($request->query('token') and $request->query('role')) {
            $token = $request->query('token');
            $role = $request->query('role');

            if (!$token) {
                return self::redirectToLogin();
            }

            $checkToken = $this->service->checkToken($token);

            if ($checkToken->getData('data')['status'] !== 'success') {
                return redirect()->route('logout');
            }

            // save token to session
            Session::put('token', $token);

            // get user profile
            $userService = new UserService();
            $user = $userService->getMyProfile()->getData('data')['data'];
            $userProfile = $user['profile'];
            $userAccount = $user['account'];

            // verify role
            if ($userAccount[$role]) {
                Session::put('role', [$role => true]);
            } else {
                return self::changeUserRole();
            }

            // save user data to session
            Session::put('account', $userAccount);
            Session::put('profile', $userProfile);
            Session::put('user_image', $user['account']['image']);
            Session::put('user_email', $user['account']['email']);

            $role = Session::get('role');

            if (isset($role['is_mhs']) and $role['is_mhs'] === true) {
                return redirect()->route('dashboard.user');
            } else {
                return redirect()->route('dashboard');
            }
        } else {
            if (Session::has('role') and Session::has('token')) {
                $role = Session::get('role');
                if (isset($role['is_mhs']) and $role['is_mhs'] === true) {
                    return redirect()->route('dashboard.user');
                } else {
                    return redirect()->route('dashboard');
                }
            }

            return self::redirectToVerifyPage();
        }
    }

    public function logout()
    {
        if (Session::exists('token')) {
            Session::remove('token');
            Session::remove('role');
            Session::remove('profile');
            Session::remove('user_image');
            Session::remove('user_email');
        }

        return self::redirectToLogout();
    }

    public function changeUserRole()
    {
        $tempSessionRole = Session::get('account');
        $data = [
            'roles' => array_filter($tempSessionRole, function ($item) {
                return is_bool($item) && $item === true;
            }),
        ];

        return view('auth.roles', $data);
    }

    private function redirectToVerifyPage()
    {
        return redirect()->away(
            config('myconfig.login.base_url') . 'verify?site=' . config('app.url')
        );
    }

    private function redirectToLogin()
    {
        return redirect()->away(
            config('myconfig.login.base_url') . 'login?site=' . config('app.url')
        );
    }

    private function redirectToLogout()
    {
        return redirect()->away(
            config('myconfig.login.base_url') . 'logout?site=' .  config('app.url')
        );
    }
}
