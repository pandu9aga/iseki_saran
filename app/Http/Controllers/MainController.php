<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Type_User;
use App\Models\Member;

class MainController extends Controller
{
    public function index(){
        if (session()->has('Id_User')) {
            return redirect()->route('dashboard');
        }
        if (session()->has('Id_Member')) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'Username_User' => 'required',
            'Password_User' => 'required'
        ]);

        $user = User::where('Username_User', $request->Username_User)->first();

        if (!$user) {
            return back()->withErrors(['loginError' => 'Invalid username or password']);
        }

        if ($request->Password_User == $user->Password_User) {
            session(['Id_User' => $user->Id_User]);
            session(['Id_Type_User' => $user->Id_Type_User]);
            session(['Username_User' => $user->Username_User]);

            return redirect()->route('dashboard');
        }

        return back()->withErrors(['loginError' => 'Invalid username or password']);
    }

    public function login_member(Request $request)
    {
        $request->validate([
            'NIK_Member' => 'required'
        ]);

        $member = Member::where('nik', $request->NIK_Member)->first();

        if (!$member) {
            return back()->withErrors(['loginError' => 'Invalid NIK']);
        }

        session(['Id_Member' => $member->id]);
        session(['NIK_Member' => $member->nik]);
        session(['Name_Member' => $member->nama]);

        return redirect()->route('home');
    }

    public function logout()
    {
        session()->forget('Id_User');
        session()->forget('Id_Type_User');
        session()->forget('Username_User');
        return redirect()->route('/');
    }

    public function logout_member()
    {
        session()->forget('Id_Member');
        session()->forget('NIK_Member');
        session()->forget('Name_Member');
        return redirect()->route('/');
    }

}