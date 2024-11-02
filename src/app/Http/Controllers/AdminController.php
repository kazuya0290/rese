<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Representative;
use Illuminate\Support\Facades\Hash;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;
use Exception;

class AdminController extends Controller
{
    // 管理者ダッシュボードを表示
    public function index()
    {
        return view('admin');
    }

    // 新しい店舗代表者を登録
    public function storeRepresentative(Request $request)
    {
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:representatives,email',
        'password' => 'required|min:8',
        ]);

    // 代表者を新規作成
    $representative = Representative::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password']),
        ]);

    if ($representative) {
        return response()->json(['success' => true]);
        }else {
        return response()->json(['success' => false, 'message' => 'データの保存に失敗しました。']);
        }
    }
    // 全利用者にお知らせメールを送信
    public function sendNotification(Request $request)
    {
        $validatedData = $request->validate([
            'subject' => 'required|max:255',
            'message' => 'required|max:255',
        ]);

        $users = User::all();
        $subject = $validatedData['subject'];
        $message = $validatedData['message'];

        try {
            foreach ($users as $user) {
                Mail::to($user->email)->send(new NotificationMail($subject, $message));
            }
            return response()->json(['success' => true, 'message' => 'お知らせメールが全利用者に送信されました']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'メール送信に失敗しました: ' . $e->getMessage()]);
        }
    }

   
    public function checkAdminPasscode(Request $request)
    {
    $passcode = $request->input('passcode');

    if ($passcode === 'admin') {
        $request->session()->put('admin_passcode', $passcode);
        return redirect()->route('admin.index');
        } else {
        return redirect()->back()->withErrors(['error' => 'パスコードが間違っています']);
        }
    }

}
