{{-- 店舗情報の表示 --}}

<x-guest-layout>
    <div class="article_frame">
        <style>
            .textLink {
                text-decoration: underline;
                font-size: 0.875rem; /* 14px */
                line-height: 1.25rem; /* 20px */
                color: #71717a;
            }
            .textLink::hover {
                color: red; /* 文字色を赤くする */
                background-color: red; /* 背景色を赤くする */
                border-color: red; /* 枠線を赤くする */
            }

            .inputBoxArea {
                display: flex; /* Flexbox を有効にする */
                flex-direction: column; /* デフォルトは縦並び */
            }

            /* 画面幅が 800px 以上の場合 */
            @media (min-width: 1000px) {
                .inputBoxArea {
                    flex-direction: row; /* 横並びにする */
                    align-items: center; /* 垂直方向に中央揃え */
                }

                .inputBoxArea label {
                    width: 8em;
                    margin-right: 10px; /* ラベルとテキストボックスの間に間隔を作る */
                }
            }            
        </style>

        <hr>
        <x-rTextbox name="baseNameKanji" type="label" value="{{ $ShopInf->spName }}">{{ __('Shop name') }}</x-rTextbox>
        <x-rTextbox name="baseNameKana"  type="label" value="{{ $ShopInf->spNameKana }}">{{ __('Shop name') }}{{ __('(KANA)') }}</x-rTextbox>
        <x-rTextbox name="baseAddrZip"   type="label" value="{{ $ShopInf->spAddrZip }}">{{ __('postal code') }}</x-rTextbox>
        <x-rTextbox name="baseAddrPref"  type="label" value="{{ $ShopInf->spAddrPref }}">{{ __('province') }}</x-rTextbox>
        <x-rTextbox name="baseAddrCity"  type="label" value="{{ $ShopInf->spAddrCity }}">{{ __('municipality') }}</x-rTextbox>
        <x-rTextbox name="baseAddrOther" type="label" value="{{ $ShopInf->spAddrOther }}">{{ __('village') }}</x-rTextbox>
        <x-rTextbox name="baseTel1"      type="label" value="{{ $ShopInf->spTel1 }}">{{ __('phone') }}1</x-rTextbox>
        <x-rTextbox name="baseTel2"      type="label" value="{{ $ShopInf->spTel2 }}">{{ __('phone') }}2</x-rTextbox>
        <x-rTextbox name="baseEMail"     type="label" value="{{ $ShopInf->spEMail }}">{{ __('Email') }}</x-rTextbox>
        <x-rTextbox name="baseURL"       type="label" value="{{ $ShopInf->spURL }}">{{ __('home URL') }}</x-rTextbox>
        <x-rTextarea id="MessageText"    attr="label" name="MessageText" msgText="{{ $ShopInf->spMsgText }}">{{ __('memo') }}</x-rTextArea><br>

        <!-- 商品一覧 -->
        @if ($ShopInf->products->count() > 0)
            <h4 class="text-lg font-bold mt-6 mb-3 border-b-2 pb-1 border-indigo-400 text-indigo-700">提供サービス/商品一覧</h4>
            
            <table class="list_table">
                <thead>
                    <tr class="bg-indigo-100">
                        <th class="product">商品名/予約</th>
                        <th class="ryokin">料金</th>
                        <th class="product">利用時間</th>
                        <th class="count">
                            定員
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-indigo-700 uppercase tracking-wider">
                            メモ
                        </th>
                        <th class="seqNo">ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($ShopInf->products as $product)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            {{-- 商品名/予約リンク --}}
                            <td class="product">
                                <a href="{{ route('reserve.calender', ['user_id' => $ShopInf->id, 'product_id' => $product->id]) }}" 
                                class="text-blue-600 hover:text-blue-800 font-semibold text-base">
                                    {{ $product->productName }}
                                </a>
                            </td>

                            {{-- 料金 --}}
                            <td class="ryokin">¥{{ number_format($product->price) }}</td>

                            {{-- 利用時間 --}}
                            <td class="product">
                                {{ \Carbon\Carbon::parse($product->TimeStart)->format('H:i') }}～{{ \Carbon\Carbon::parse($product->TimeEnd)->format('H:i') }}
                            </td>

                            {{-- 定員 --}}
                            <td class="count">{{ number_format($product->capacity) }} 名</td>

                            {{-- メモ --}}
                            <td class="px-4 py-4 text-sm text-gray-600 max-w-xs truncate">
                                {{ $product->memo }}
                            </td>
                            
                            {{-- 商品ID (非表示または小さく表示) --}}
                            <td class="seqNo"> {{ $product->id }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @else
            <p class="text-md text-gray-500 mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">この店舗には現在、予約可能なサービス/商品は登録されていません。</p>
        @endif


        <!-- アクセサリー一覧 (accessories) ここから追加 -->
        @if ($ShopInf->accessories->count() > 0)
            <h4 class="text-lg font-bold mt-10 mb-3 border-b-2 pb-1 border-indigo-400 text-indigo-700">追加オプション/アクセサリー一覧</h4>
            
            <table class="list_table">
                <thead>
                    <tr class="bg-yellow-100">
                        <th class="product">アクセサリー名</th>
                        <th class="ryokin">料金</th>
                        <th class="product">状態</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider" colspan="2">
                            メモ
                        </th>
                        <th class="seqNo">コードID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($ShopInf->accessories as $accessory)
                        {{-- IsEnabled が 1 のもののみ表示する場合は、ここでフィルタリングロジックを追加できます --}}
                        {{-- @if ($accessory->IsEnabled == 1) --}} 
                            <tr class="hover:bg-gray-50 transition duration-150">
                                {{-- アクセサリー名 --}}
                                <td class="product font-semibold text-base text-gray-800">
                                    {{ $accessory->productName }}
                                </td>

                                {{-- 料金 --}}
                                <td class="ryokin text-green-700 font-medium">
                                    ¥{{ number_format($accessory->price) }}
                                </td>

                                {{-- 状態 (IsEnabled) --}}
                                <td class="product text-sm">
                                    @if ($accessory->IsEnabled == 1)
                                        <span class="text-green-600">有効</span>
                                    @else
                                        <span class="text-red-600">無効</span>
                                    @endif
                                </td>

                                {{-- メモ --}}
                                <td class="px-4 py-4 text-sm text-gray-600 max-w-xs truncate" colspan="2">
                                    {{ $accessory->memo }}
                                </td>
                                
                                {{-- 商品名コード (productID) --}}
                                <td class="seqNo text-xs text-gray-500"> {{ $accessory->productID }}</td>
                            </tr>
                        {{-- @endif --}}
                    @endforeach
                </tbody>
            </table>

        @else
            <p class="text-md text-gray-500 mt-10 p-4 bg-gray-50 border border-gray-200 rounded-lg">この店舗には現在、追加オプション/アクセサリーは登録されていません。</p>
        @endif
        <!-- アクセサリー一覧 (accessories) ここまで追加 -->

    </div>
</x-guest-layout>
<!--「resources\views\auth\edituser.blade php」 -- END<br> -->
