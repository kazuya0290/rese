<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\Representative;
use App\Http\Requests\RepresentativeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RepresentativeController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.representative_login');
    }

    public function login(RepresentativeRequest $request)
{
    $validated = $request->validated();
    
    
    $credentials = $request->only('email', 'password');

    if (Auth::guard('representative')->attempt($credentials)) {
        session()->flash('login_success', 'ログインしました'); 
        return redirect()->intended('/');
    } else {
         session()->flash('login_error', '認証に失敗しました、メールアドレスかパスワードが間違っています');
        return redirect()->back();
    }
}

    public function index()
    {
    
    $reservations = Reservation::with(['user', 'shop'])->paginate(7);
    return view('representative', compact('reservations'));
    
    }

    protected function authenticated(Request $request, $user)
    {

    session()->flash('login_success', 'ログインしました');
    return redirect()->route('/'); 

    }

    
    public function logout(Request $request)
    {
        Auth::guard('representative')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('representative.login'); 
    }
    
    
    public function getReservations()
    {
        $reservations = Reservation::all(); 
        return response()->json(['reservations' => $reservations]);
    }
}