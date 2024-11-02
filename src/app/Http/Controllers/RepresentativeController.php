<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RepresentativeController extends Controller
{
    // ログインフォームの表示
    public function showLoginForm()
    {
        return view('auth.representative_login');
    }

    // ログイン処理
    public function login(Request $request)
    {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string|min:8',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::guard('representative')->attempt($credentials)) {
        session()->flash('login_success', 'ログインしました'); // セッションメッセージを設定
        return redirect()->intended('/'); // ホーム画面にリダイレクト
        } else {
        return redirect()->back()->withErrors(['login_error' => '認証に失敗しました。']);
        }
    }

    // ダッシュボードの表示
    public function index()
    {
    // 予約情報を取得し、ユーザー情報も一緒に取得
    $reservations = Reservation::with('user')->paginate(7);

    // 予約情報をビューに渡す
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
    
    // 予約データの取得
    public function getReservations()
    {
        $reservations = Reservation::all(); 
        return response()->json(['reservations' => $reservations]);
    }
}
