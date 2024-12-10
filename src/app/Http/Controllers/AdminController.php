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
use League\Csv\Reader;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        $review = Review::find($id);
    
        if ($review) {
        $review->delete();
        
            return response()->json([
                'success' => true, 
                'message' => '口コミが削除されました'
            ]);
        }
    
        return response()->json([
            'success' => false, 
            'message' => '口コミが見つかりません'
        ], 404);
    }

   public function importCsv(Request $request)
    {
            $request->validate([
                'csvFile' => [
                'required', 
                'file', 
                'mimes:csv,txt', 
                function ($attribute, $value, $fail) {
                if ($value->getSize() === 0) {
                    $fail('空のファイルはアップロードできません。');
                }
                
                $path = $value->getRealPath();
                $data = array_map('str_getcsv', file($path));
                
                if (count($data) <= 1) {
                    $fail('CSVファイルにデータが存在しません。');
                    }
                },
            ],
        ]);

    $csvFile = $request->file('csvFile');
    $csv = Reader::createFromPath($csvFile->getRealPath(), 'r');
    $csv->setHeaderOffset(0);

    $records = $csv->getRecords();
    $errors = [];
    $rowNumber = 2;

    foreach ($records as $record) {
       
        $emptyRecord = true;
        foreach ($record as $value) {
            if (trim($value) !== '') {
                $emptyRecord = false;
                break;
            }
        }

        if ($emptyRecord) {
            $errors[] = "行{$rowNumber}: すべてのフィールドが空白または未入力です。";
            $rowNumber++;
            continue;
        }

        $customMsgs = [
            '店舗名.required' => "行{$rowNumber}: 店舗名は50文字以内で入力してください",
            '店舗名.max' => "行{$rowNumber}: 店舗名は50文字以内で入力してください",
            '地域.required' => "行{$rowNumber}: 地域は必須です",
            '地域.integer' => "行{$rowNumber}: 地域は1, 2, 3のいずれかを指定してください",
            '地域.in' => "行{$rowNumber}: 地域は1(東京都), 2(大阪府), 3(福岡県)のいずれかを指定してください",
            'ジャンル.required' => "行{$rowNumber}: ジャンルは必須です",
            'ジャンル.integer' => "行{$rowNumber}: ジャンルは1, 2, 3, 4, 5のいずれかを指定してください",
            'ジャンル.in' => "行{$rowNumber}: ジャンルは1(寿司), 2(焼肉), 3(イタリアン), 4(居酒屋), 5(ラーメン)のいずれかを指定してください",
            '店舗概要.required' => "行{$rowNumber}: 店舗概要は必須です",
            '店舗概要.max' => "行{$rowNumber}: 店舗概要は400文字以内で入力してください",
            '画像URL.required' => "行{$rowNumber}: 画像URLは必須です",
            '画像URL.url' => "行{$rowNumber}: 画像URLは、URL形式で「jpeg」「png」のみアップロード可能です",
            '画像URL.regex' => "行{$rowNumber}: 画像URLは、URL形式で「jpeg」「png」のみアップロード可能です",
        ];

        $validator = Validator::make($record, [
            '店舗名' => 'required|max:50',
            '地域' => 'required|in:1,2,3',
            'ジャンル' => 'required|in:1,2,3,4,5',
            '店舗概要' => 'required|max:400',
            '画像URL' => ['required', 'url', 'regex:/\.(jpg|png)$/i'],
        ], $customMsgs);

        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $field => $messages) {
                foreach ($messages as $message) {
                    $errors[] = $message;
                }
            }

        } else {
        
                Shop::create([
                    'name' => $record['店舗名'],
                    'area_id' => $record['地域'], 
                    'genre_id' => $record['ジャンル'],
                    'description' => $record['店舗概要'],
                    'image' => $record['画像URL'],
                ]);
            }

        $rowNumber++;
        }

        if (!empty($errors)) {
            return back()->withErrors($errors);
        }

    return back()->with('success', 'CSVファイルのインポートが完了しました');
    }
}