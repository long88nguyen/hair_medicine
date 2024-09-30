<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginForm()
    {
        if(Auth::check()){
            return redirect()->back();
        }
        return view('auth.login');
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
}
