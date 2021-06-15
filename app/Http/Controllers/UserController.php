<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function create()//возвращается вид
    {
        return view('user.create');
    }

    public function store(Request $request)
    {  //правила валидации!!!!!!!!!!!!!!!
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);
//сохраняем пользователя
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)  
        ]);
//
        session()->flash('success', 'Регистрация пройдена');
        Auth::login($user);//авторизация пользователя
        return redirect()->home();
    }

    public function loginForm()//форма для автоиизации вид
    {
        return view('user.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
//авторизовать пользователя
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {
            session()->flash('success', 'You are logged');//пользователь авторизован
            //проверяем автоизованый пользователь является ли он администратором
            if (Auth::user()->is_admin) {
                return redirect()->route('admin.index');//если админ отправим в авминовскую
            } else {
                return redirect()->home();//если пользоваель на главную
            }
        }

        return redirect()->back()->with('error', 'Incorrect login or password');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.create');
    }

}