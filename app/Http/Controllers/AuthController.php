<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function loginForm()
    {
        if(Auth::check()){
            return redirect()->back();
        }
        return view('auth.login2');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

         // Nếu validate thành công, kiểm tra đăng nhập
         $credentials = $request->only('email', 'password');
         if (Auth::attempt($credentials)) {
             // Nếu đăng nhập thành công, chuyển hướng đến dashboard
             return redirect()->route('dashboard')->with('success', 'Đăng nhập thành công');
         }
 
         // Nếu đăng nhập thất bại, trả về lỗi
         return redirect()->back()->with('error', 'Email hoặc mật khẩu không đúng.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function redirectSocial($service)
    {
        return Socialite::driver($service)->redirect();
    }

    public function callbackSocial($service)
    {
        $userSocial = Socialite::driver($service)->user();

        $user = User::where('provider', $service)
            ->where('provider_id', $userSocial->id)
            ->first();
        if (!$user) {
            $user = User::create([
                'name' => $userSocial->name,
                'email' => $userSocial->email,
                'phone' => null,
                'provider' => $service,
                'provider_id' => $userSocial->id,
                'avatar' => $userSocial->avatar,
                'avatar_original' => $userSocial->avatar_original,
                'token' => $userSocial->token,
            ]);
            Auth::login($user);
        } else {
            $user->update([
                'name' => $userSocial->name,
                'email' => $userSocial->email,
                'avatar' => $userSocial->avatar,
                'avatar_original' => $userSocial->avatar_original,
                'token' => $userSocial->token,
            ]);
            Auth::login($user);
        }

        return redirect()->route('dashboard')->with('success', 'Đăng nhập thành công');
    }

    
}
