<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Representative;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;
use Exception;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin');
    }

    public function storeRepresentative(AdminRequest $request)
    {
    $validated = $request->validated();

    Representative::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    
    return response()->json(['success' => true, 'message' => '代表者が追加されました']);
    }

    public function sendNotification(Request $request)
    {
        $validatedData = $request->validate([
            'subject' => 'required|max:191',
            'message' => 'required|max:191',
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
}
