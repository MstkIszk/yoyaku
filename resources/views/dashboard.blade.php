<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="article_frame">
            {{--<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{ __("You're logged in!") }}
            </div>
            --}}


            <x-rTextbox type="label" name="name" value="{{ $user->name }}" attr="required autofocus" autocomplete="name" >{{ __('UserID') }}</x-rTextbox>
            {{--
            <x-rTextbox type="password" name="password" value="{{old('password')}}" attr="required" autocomplete="new-password" >{{ __('password') }}</x-rTextbox>
            <x-rTextbox type="password" name="password_confirmation" value="{{old('password_confirmation')}}" attr="required" autocomplete="new-password" >{{ __('Confirm Password') }}</x-rTextbox>
            <hr>
            --}}

            <x-rTextbox name="baseNameKanji" type="label" value="{{ $user->spName }}">{{ __('Shop name') }}</x-rTextbox>
            <x-rTextbox name="baseNameKana" type="label" value="{{ $user->spNameKana }}">{{ __('Shop name') }}{{ __('(KANA)') }}</x-rTextbox>
            
            <label>{{ __('validation.attributes.address') }}</label>
            <x-rTextbox name="baseAddrZip"   type="label" value="{{ $user->spAddrZip }}">{{ __('postal code') }}:</x-rTextbox>
            <x-rTextbox name="baseAddrPref"  type="label" value="{{ $user->spAddrPref }}">{{ __('province') }}:</x-rTextbox>
            <x-rTextbox name="baseAddrCity"  type="label" value="{{ $user->spAddrCity }}">{{ __('municipality') }}:</x-rTextbox>
            <x-rTextbox name="baseAddrOther"  type="label" value="{{ $user->spAddrOther }}">{{ __('village') }}:</x-rTextbox>
            <x-rTextbox name="baseTel1" type="label" value="{{ $user->spTel1 }}">{{ __('phone') }}1:</x-rTextbox>
            <x-rTextbox name="baseTel2" type="label" value="{{ $user->spTel2 }}">{{ __('phone') }}2:</x-rTextbox>
            <x-rTextbox name="baseEMail" type="label" required value="{{ $user->spEMail }}">{{ __('Email') }}:</x-rTextbox>
            <x-rTextbox name="baseURL" type="label" value="{{ $user->spURL }}">URL:</x-rTextbox>
            <x-rTextarea name="MessageText" attr="label" msgText="{{ $user->spMsgText }}">{{ __('memo') }}:</x-rTextarea><br>

            <a href="{{ route('profile.edit') }}" class="link-button"> {{ __("Edit profile") }} </a>

        </div>


        <!-- user_productsのリストをtableで表示 -->
        <div class=article_frame>
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2 flex justify-between items-center">
                    登録商品/サービス一覧 ({{ $user->spName }})
                    {{-- <a href="{{ route('user_products.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        + 新しい商品を登録
                    </a> --}}
                </h3>

                @if ($products->isEmpty())
                    <p class="text-gray-500">現在、登録されている商品やサービスはありません。</p>
                @else
                    <div class="article_frame">
                        <table class="list_table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">商品/サービス名</th>
                                    <th scope="col">期間</th>
                                    <th scope="col">時間</th>
                                    <th scope="col">定員</th>
                                    <th scope="col">料金</th>
                                    <th scope="col">操作</th>
                                    <th scope="col" ><span class="sr-only">編集</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($products as $product)
                                    {{-- 1行目: 基本情報 --}}
                                    <tr class="{{ $product->IsEnabled == 0 ? 'bg-red-50 opacity-70' : '' }}">
                                        <td class="seqNo" rowspan="2"> {{ $product->productID }} </td>
                                        <td class="product">  {{ $product->productName }} </td>
                                        <td class="date_period">
                                            {{ \Carbon\Carbon::parse($product->DateStart)->format('Y/m/d') }}
                                            〜 {{ \Carbon\Carbon::parse($product->DateEnd)->format('Y/m/d') }}
                                        </td>
                                        <td class="time_period">
                                            {{ \Carbon\Carbon::parse($product->TimeStart)->format('H:i') }}
                                            〜 {{ \Carbon\Carbon::parse($product->TimeEnd)->format('H:i') }}
                                        </td>
                                        <td class="count">{{ $product->capacity }}</td>
                                        <td class="status">
                                            @if ($product->IsEnabled == 1)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    有効
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    無効
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('user_products.edit', $product->id) }}" class="link-button table-link-button">編集</a>
                                        </td>
                                    </tr>

                                    <tr>
                                        {{-- 2行目: 予約コース情報 --}}
                                        <td colspan="5" class="px-3 py-1 border-t border-dashed border-gray-200 bg-gray-50">
                                            @if ($product->courses->isEmpty())
                                                <p class="text-xs text-gray-500 italic">コースが登録されていません。</p>
                                            @else
                                                <table class="min-w-full text-xs bg-white border border-gray-200 rounded-lg">
                                                    <thead class="bg-gray-100">
                                                        <tr>
                                                            <th class="px-2 py-1 text-left font-medium text-gray-600">コース名</th>
                                                            <th class="px-2 py-1 text-right font-medium text-gray-600">平日料金</th>
                                                            <th class="px-2 py-1 text-right font-medium text-gray-600">休日料金</th>
                                                            <th class="px-2 py-1 text-center font-medium text-gray-600">状態</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($product->courses as $course)
                                                            <tr class="border-t border-gray-100 hover:bg-yellow-50 {{ $course->IsEnabled == 0 ? 'opacity-70' : '' }}">
                                                                <td class="px-2 py-1 text-gray-800">{{ $course->courseName }}</td>


                                                                {{-- コース料金（UserCoursePrice）のデータを表示するセル（colspan="2"で結合） --}}
                                                                <td colspan="2" class="px-2 py-1 text-left text-gray-700">
                                                                    {{-- userCoursePricesリレーションをループして表示 --}}
                                                                    @if ($course->userCoursePrices->isEmpty())
                                                                        <span class="text-xs text-red-500 italic">料金設定がありません。</span>
                                                                    @else
                                                                        @foreach ($course->userCoursePrices as $price)
                                                                            <div class="mb-1 last:mb-0">
                                                                                <span class="font-medium text-gray-800">{{ $price->priceName }}</span>:
                                                                                平日:¥{{ number_format($price->weekdayPrice) }} 
                                                                                休日:¥{{ number_format($price->weekendPrice) }}
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </td>

                                                                {{--
                                                                <td class="px-2 py-1 text-right text-gray-700">
                                                                    平日:¥{{ number_format($course->weekdayPrice) }}
                                                                </td>
                                                                <td class="px-2 py-1 text-right text-gray-700">
                                                                    休日:¥{{ number_format($course->weekendPrice) }}
                                                                </td>
                                                                --}}
                                                                <td class="px-2 py-1 text-center">
                                                                    @if ($course->IsEnabled == 1)
                                                                        <span class="text-green-500">〇</span>
                                                                    @else
                                                                        <span class="text-red-500">×</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </td>
                                        <td class="px-3 py-1 whitespace-nowrap text-right text-sm font-medium border-t border-dashed border-gray-200 bg-gray-50">
                                            {{-- UserCourseController::create に productID を渡す --}}
                                            <a href="{{ route('user_courses.create', ['product_id' => $product->productID]) }}" class="link-button table-link-button bg-yellow-500 hover:bg-yellow-600">
                                                コース編集
                                            </a>
                                        </td>
                                    </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <a href="{{ route('user_products.create') }}" class="link-button">
                {{ __('new product') }}
            </a>
        </div>

        
        <!-- user_accessoriesのリストをtableで表示 -->
        <div class="article_frame mt-8">
            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg border-b border-gray-200">
                <h3 class="text-xl font-bold mb-4 border-b pb-2 flex justify-between items-center text-yellow-700">
                    登録オプション/アクセサリー一覧 ({{ $user->spName }})
                </h3>

                @if ($accessories->isEmpty())
                    <p class="text-gray-500">現在、登録されているオプションやアクセサリーはありません。</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="list_table">
                            <thead class="bg-gray-50">
                                <tr class="bg-yellow-50">
                                    <th scope="col" class="seqNo">ID</th>
                                    <th scope="col" class="product">アクセサリー名</th>
                                    <th scope="col" class="ryokin">料金</th>
                                    <th scope="col" class="status">状態</th>
                                    <th scope="col" class="memo">メモ</th>
                                    <th scope="col" class="action"><span class="sr-only">編集</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($accessories as $accessory)
                                    <tr class="{{ $accessory->IsEnabled == 0 ? 'bg-red-50 opacity-70' : '' }} hover:bg-gray-100">
                                        <td class="seqNo text-gray-500"> {{ $accessory->productID }} </td>
                                        <td class="product font-medium">  {{ $accessory->productName }} </td>
                                        <td class="ryokin font-semibold text-green-700"> ¥{{ number_format($accessory->price) }}</td>
                                        <td class="status">
                                            @if ($accessory->IsEnabled == 1)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    有効
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    無効
                                                </span>
                                            @endif
                                        </td>
                                        <td class="memo text-sm text-gray-600 truncate">
                                            {{ $accessory->memo }}
                                        </td>
                                        <td class="action">
                                            {{-- <a href="{{ route('user_accessories.edit', $accessory->id) }}" class="link-button table-link-button bg-yellow-600 hover:bg-yellow-700">編集</a> --}}
                                            編集
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                <a href="{{ route('user_accesories.create') }}" class="link-button bg-yellow-600 hover:bg-yellow-700">
                    {{ __('new accessory') }}
                </a>
            </div>
        </div>

        <hr>
        <a href="{{ url('/logout') }}" class="link-button">
            {{ __('Logout') }}
        </a>

        </div>
    </div>
    
</x-app-layout>
