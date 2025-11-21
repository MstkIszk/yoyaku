<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('course prise') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <x-controltemfileio nameitem="courseName" extension=".pryokin"></x-controltemfileio>

        <form action="{{ route('UserCoursePrice.store') }}" method="POST">
            @csrf

            {{-- コース情報の表示 --}}
            <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
                <div class="grid grid-cols-2 gap-4">
                <x-rTextbox name="user_name"    type="label" value="{{ $user->spName }}">店名</x-rTextbox>
                <x-rTextbox name="productName"  type="label" value="{{ $userProduct->productName ?? '未設定' }}">商品名</x-rTextbox>
                <x-rTextbox name="courseName"   type="label" value="{{ $userCourse->courseName ?? '未設定' }}">コース名</x-rTextbox>
            </div>
            <input type="hidden" name="productID" value="{{ $userProduct->id }}">
            <input type="hidden" name="courseID" value="{{ $userCourse->id }}">

            <div id="price-list" class="bg-white shadow-lg rounded-lg overflow-hidden">
                <table class="list_table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="seqNo">ID</th>
                            <th class="seqNo">コースコード</th>
                            <th class="seqNo">料金コード</th>
                            <th class="product">料金名</th>
                            <th class="ryokin">平日料金</th>
                            <th class="ryokin">休日料金</th>
                            <th class="status">有効</th>
                            <th class="memo">メモ</th>
                            <th class="px-3 py-3 w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="price-rows">
                        @php
                            // 既存のコースデータを取得。渡されていない場合は空のCollectionとして扱う
                            $existingCourses = $courses ?? collect();
                            $minRows = 10;
                            $priceCodeIx = 1;
                            // 初期表示する行数は、既存データの件数と最低10行の多い方とする
                            $totalRows = max($minRows, $existingCourses->count());
                        @endphp


                        {{-- 既存の料金データを表示 --}}
                        @forelse ($userCoursePrices as $index => $price)

                        <tr class="hover:bg-gray-50">
                            <td class="seqNo">
                                <input type="number" name="prices[{{ $index }}][id]" value="{{ $price->id ?? '' }}">
                                {{ $price->id ?? '-' }}
                            </td>
                            <td class="seqNo">
                                <input type="number" name="prices[{{ $index }}][priceCode]" value="{{ $price->priceCode ?? 0 }}" 
                                    class="innerLabel txtWidth_2 text-center" placeholder="表示順">
                            </td>
                            <td class="product">
                                <input type="text" name="prices[{{ $index }}][priceName]" value="{{ $price->priceName ?? '' }}" 
                                    class="innerText txtWidth_90pc" placeholder="料金名">
                            </td>
                            <td class="ryokin">
                                <input type="number" name="prices[{{ $index }}][weekdayPrice]" value="{{ $price->weekdayPrice ?? 0 }}"
                                    class="innerText ryokin" placeholder="0">
                            </td>
                            <td class="ryokin">
                                <input type="number" name="prices[{{ $index }}][weekendPrice]" value="{{ $price->weekendPrice ?? 0 }}"
                                    class="innerText ryokin" placeholder="0">
                            </td>
                            <td class="text-center status">
                                <div class="checkbox-line-button">
                                    <div class="checkbox-button-container">
                                        <input type="checkbox" name="prices[{{ $index }}][IsEnabled]" id="courses-1-IsEnabled" class="checkbox-line" value="1" checked="">
                                        <label class="toggle-label" for="courses-{{ $index }}-IsEnabled"></label>
                                    </div>
                                </div>
                            </td>
                            <td class="memo">
                                <input type="text" name="prices[{{ $index }}][memo]" value="{{ $price->memo ?? '' }}"
                                    class="form-input block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="備考">
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-center">
                                <button type="button" class="remove-row text-red-600 hover:text-red-900 text-lg">&times;</button>
                            </td>
                        </tr>

                        @php
                            if($price->priceCode >= $priceCodeIx) {
                                $priceCodeIx = $price->priceCode + 1;
                            }
                        @endphp
                        @empty
                        @endforelse

                        {{-- 初期表示の空行 --}}
                        @for ($index = $existingCourses->count(); $index < $totalRows; $index++)

                        <tr class="hover:bg-gray-50">
                            <td class="seqNo">
                                <input type="number" name="prices[{{ $index }}][id]" value=""
                                    class="innerLabel txtWidth_2 text-center">
                            </td>
                            <td class="seqNo">
                                <input type="number" name="prices[{{ $index }}][priceCode]" value="{{ $priceCodeIx }}0" 
                                    class="innerLabel txtWidth_2 text-center" placeholder="表示順">
                            </td>
                            <td class="product">
                                <input type="text" name="prices[{{ $index }}][priceName]" value="" 
                                    class="innerText txtWidth_90pc" placeholder="料金名 (必須)">
                            </td>
                            <td class="ryokin">
                                <input type="number" name="prices[{{ $index }}][weekdayPrice]" value="0"
                                    class="innerText ryokin" placeholder="0">
                            </td>
                            <td class="ryokin">
                                <input type="number" name="prices[{{ $index }}][weekendPrice]" value="0"
                                    class="innerText ryokin" placeholder="0">
                            </td>
                            <td class="text-center status">
                                <div class="checkbox-line-button">
                                    <div class="checkbox-button-container">
                                        <input type="checkbox" name="prices[{{ $index }}][IsEnabled]" id="courses-1-IsEnabled" class="checkbox-line" value="1" checked="">
                                        <label class="toggle-label" for="courses-{{ $index }}-IsEnabled"></label>
                                    </div>
                                </div>
                            </td>
                            <td class="memo">
                                <input type="text" name="prices[{{ $index }}][memo]" value=""
                                    class="form-input block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="備考">
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap text-center">
                                <button type="button" class="remove-row text-red-600 hover:text-red-900 text-lg">&times;</button>
                            </td>
                        </tr>

                        @php
                            $priceCodeIx++;
                        @endphp

                        @endfor

                    </tbody>
                </table>
            </div>

            <div class="flex justify-between mt-6">
                <div>
                    {{-- 適切な戻り先ルートに変更してください --}}
                    <a href="{{ route('dashboard') }}" class="link-button bg-gray-500 hover:bg-gray-600 focus:ring-gray-500 mr-4">
                        {{ __('キャンセル') }}
                    </a>
                    <button type="submit" class="link-button bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500">
                        {{ __('登録/更新') }}
                    </button>
                </div>
            </div>
        </form>
    </div>


</x-app-layout>
