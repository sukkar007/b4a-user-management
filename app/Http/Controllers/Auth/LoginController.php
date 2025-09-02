<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    /**
     * عرض نموذج تسجيل الدخول.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * التعامل مع طلب تسجيل الدخول.
     */
    public function login(Request $request): RedirectResponse
    {
        // 1. التحقق من أن الحقول المطلوبة موجودة
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. محاولة تسجيل الدخول باستخدام نظام المصادقة المخصص (parse)
        if (Auth::attempt($credentials)) {
            // إذا نجح تسجيل الدخول
            $request->session()->regenerate();

            // 3. التحقق إذا كان المستخدم لديه صلاحية "admin"
            if (Auth::user() && Auth::user()->isAdmin()) {
                // إذا كان admin، قم بتوجيهه إلى لوحة التحكم
                return redirect()->intended('/admin/dashboard');
            }
            
            // إذا لم يكن admin، قم بتسجيل خروجه فوراً وأظهر رسالة خطأ
            Auth::logout();
            
            return back()->withErrors([
                'username' => 'You do not have admin privileges to access the dashboard.',
            ])->onlyInput('username');
        }

        // 4. إذا فشلت محاولة تسجيل الدخول من البداية (بيانات خاطئة)
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records in Back4App.',
        ])->onlyInput('username');
    }

    /**
     * تسجيل خروج المستخدم.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

