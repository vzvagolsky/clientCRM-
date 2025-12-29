<?php

namespace App\Http\Controllers\Manager\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\ManagerLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('manager.auth.login');
    }

    public function login(ManagerLoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Вход разрешён только менеджеру
            if (! $request->user()->hasRole('manager')) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Нет доступа к панели менеджера',
                ])->onlyInput('email');
            }

            return redirect()->route('manager.tickets.index');
        }

        return back()->withErrors([
            'email' => 'Неверный email или пароль',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('manager.login');
    }
}