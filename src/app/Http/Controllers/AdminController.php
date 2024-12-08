<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\AdminMailRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Representative;
use App\Models\Review;
use App\Models\Shop;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;
use Exception;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

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

    public function sendNotification(AdminMailRequest $request)
    {
        $validated = $request->validated();
        
        $subject = $validated['subject'];
        $message = $validated['message'];
        
        $users = User::all();
        
        try {
            foreach ($users as $user) {
                Mail::to($user->email)->send(new NotificationMail($subject, $message));
            }
            return response()->json(['success' => true, 'message' => 'お知らせメールが全利用者に送信されました']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'メール送信に失敗しました: ' . $e->getMessage()]);
        }
    }

    public function getAllReviews()
    {
        $reviews = Review::with('user', 'shop')
            ->get()
            ->map(function ($review) {
                return [
                    'id' => $review->id,
                    'user_name' => $review->user->name ?? '不明なユーザー',
                    'created_at_formatted' => $review->created_at->format('Y年m月d日'),
                    'comment' => $review->comment,
                    'image' => $review->image ? asset('storage/' . $review->image) : null,
                    'shop_name' => $review->shop->name ?? '不明な店舗',
                ];
            });

        return response()->json(['reviews' => $reviews]);
    }

    public function destroy($id)
    {
    // レビューの取得
        $review = Review::find($id);
    
    // レビューが存在すれば削除
        if ($review) {
        $review->delete();
        
        // JSONレスポンスを返す
            return response()->json([
                'success' => true, 
                'message' => '口コミが削除されました'
            ]);
        }
    
    // レビューが見つからない場合はエラーレスポンスを返す
        return response()->json([
            'success' => false, 
            'message' => '口コミが見つかりません'
        ], 404);
    }

      public function importCsv(Request $request)
    {
    // 🛠️ CSVファイルのバリデーション
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt',
    ]);

    $file = $request->file('csv_file');

    // 🛠️ CSVファイルの保存 (storage/app/public/csvs に保存)
    $path = $file->storeAs('public/csvs', $file->getClientOriginalName());

    // 🛠️ CSVデータの処理
    $filePath = storage_path('app/' . $path);
    $data = array_map('str_getcsv', file($filePath));

    // 🛠️ CSVの1行目はヘッダーの場合が多いので、削除する
    $header = array_shift($data); 

    // 🛠️ CSVの内容をshopsテーブルに登録する
    foreach ($data as $row) {
        try {
            // CSVのカラムが ["name", "area_id", "genre_id", "hashtags", "description", "image"] であると仮定
            Shop::create([
                'name'        => $row[0] ?? '未定義の名前',  // CSVの1列目
                'area_id'     => $row[1] ?? 1,  // CSVの2列目（地域ID）
                'genre_id'    => $row[2] ?? 1,  // CSVの3列目（ジャンルID）
                'description' => $row[3] ?? '説明がありません',  // CSVの5列目（店舗概要）
                'image'       => $row[4] ?? null,  // CSVの6列目（画像URL）
            ]);
        } catch (\Exception $e) {
            \Log::error('CSVのインポート中にエラーが発生しました', ['エラー' => $e->getMessage()]);
        }
    }

    return response()->json([
        'success' => true,
        'message' => 'CSVファイルが正常にインポートされました。',
        ]);
    }

}

