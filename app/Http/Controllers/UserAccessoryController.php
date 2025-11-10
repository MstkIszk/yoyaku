<?php

namespace App\Http\Controllers;

use App\Models\UserAccessory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserAccessoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        // 認証済みユーザーのID (baseCode) に紐づくアクセサリーリストを取得
        // productIDで昇順にソートして、既存データを表示しやすいようにする
        $accessories = $user->accessories()
                            ->orderBy('productID', 'asc')
                            ->get();

        // Viewに既存データを渡す
        return view('user_accessory.AcCreate', compact('user','accessories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $accessoriesData = $request->input('accessories', []);
        $accessoriesToSave = [];

        // フォームから送信されたデータを処理
        foreach ($accessoriesData as $data) {
            // productName と price が空でない行のみを処理対象とする
            if (!empty($data['productName']) && is_numeric($data['price'])) {
                $accessoriesToSave[] = [
                    'id' => $data['id'] ?? null, 
                    'productID' => $data['productID'] ?? null, 
                    'productName' => $data['productName'],
                    'price' => (int) $data['price'],
                    'IsEnabled' => isset($data['IsEnabled']) ? 1 : 0, 
                    'memo' => $data['memo'] ?? null,
                ];
            }
        }

        if (empty($accessoriesToSave)) {
            return redirect()->back()->withErrors(['msg' => '登録または更新するオプション/アクセサリーのデータがありません。'])
                                     ->withInput();
        }

        DB::beginTransaction();
        try {
            $newlyCreatedCount = 0;

            foreach ($accessoriesToSave as $item) {
                // IDがあれば更新、なければ新規作成
                if (!empty($item['id'])) {
                    $accessory = UserAccessory::where('id', $item['id'])
                                              ->where('baseCode', $user->id)
                                              ->first();
                    if ($accessory) {
                        $accessory->update([
                            'productID' => $item['productID'],
                            'productName' => $item['productName'],
                            'price' => $item['price'],
                            'IsEnabled' => $item['IsEnabled'],
                            'memo' => $item['memo'],
                        ]);
                    }
                } else {
                    // 新規作成の場合、productIDを採番
                    $maxProductId = UserAccessory::where('baseCode', $user->id)->max('productID') ?? 0;
                    $newProductId = $maxProductId + 1;

                    UserAccessory::create([
                        'baseCode' => $user->id,
                        'productID' => $newProductId,
                        'productName' => $item['productName'],
                        'price' => $item['price'],
                        'IsEnabled' => $item['IsEnabled'],
                        'memo' => $item['memo'],
                    ]);
                    $newlyCreatedCount++;
                }
            }

            DB::commit();

            // 保存成功後、ダッシュボードにリダイレクト
            return redirect()->route('dashboard')->with('status', $newlyCreatedCount . '件のアクセサリーが登録・更新されました。');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Accessory save failed: ' . $e->getMessage());
            
            return redirect()->back()->withErrors(['msg' => 'オプション/アクセサリーの保存中にエラーが発生しました。'])
                                     ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserAccessory $userAccessory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserAccessory $userAccessory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserAccessory $userAccessory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAccessory $userAccessory)
    {
        //
    }
}
