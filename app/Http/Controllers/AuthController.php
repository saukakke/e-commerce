<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm(){ return view('auth.login'); }
    public function login(Request $request){
        $data=$request->validate(['email'=>'required|email','password'=>'required|string']);
        if(!Auth::attempt($data,$request->boolean('remember'))){ return back()->withErrors(['email'=>'The credentials are incorrect.'])->withInput(); }
        $request->session()->regenerate();
        return redirect()->intended(route('account'))->with('success','Welcome back.');
    }
    public function registerForm(){ return view('auth.register'); }
    public function register(Request $request){
        $data=$request->validate(['name'=>'required|string|max:120','email'=>'required|email|max:190|unique:users','phone'=>'nullable|string|max:30','password'=>'required|string|min:8|confirmed']);
        $user=User::create($data+['role'=>'customer']); Auth::login($user); $request->session()->regenerate();
        return redirect()->route('account')->with('success','Your account has been created.');
    }
    public function logout(Request $request){ Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return redirect()->route('home'); }
}
