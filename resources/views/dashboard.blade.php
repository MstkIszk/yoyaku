<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="article_frame">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{ __("You're logged in!") }}
            </div>


            <x-rTextbox type="label" name="name" value="{{ $user->name }}" attr="required autofocus" autocomplete="name" >{{ __('UserID') }}</x-rTextbox>
            {{--
            <x-rTextbox type="password" name="password" value="{{old('password')}}" attr="required" autocomplete="new-password" >{{ __('password') }}</x-rTextbox>
            <x-rTextbox type="password" name="password_confirmation" value="{{old('password_confirmation')}}" attr="required" autocomplete="new-password" >{{ __('Confirm Password') }}</x-rTextbox>
            --}}

            <hr>
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
            <x-rTextarea name="MessageText" msgText="{{ $user->spMsgText }}"></x-rTextarea><br>

            <a href="{{ route('profile.edit') }}" class="link-button"> {{ __("Edit profile") }} </a>

        </div>div>


        <!-- user_productsのリストをtableで表示 -->
        <div class=article_frame">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2 flex justify-between items-center">
                    登録商品/サービス一覧 ({{ $user->spName }})
                    <a href="{{ route('user_products.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        + 新しい商品を登録
                    </a>
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
                                    <th scope="col">状態</th>
                                    <th scope="col" ><span class="sr-only">編集</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($products as $product)
                                    <tr class="{{ $product->IsEnabled == 0 ? 'bg-red-50 opacity-70' : '' }}">
                                        <td class="seqNo"> {{ $product->productID }} </td>
                                        <td class="product">  {{ $product->productName }} </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($product->DateStart)->format('Y/m/d') }}
                                            〜 {{ \Carbon\Carbon::parse($product->DateEnd)->format('Y/m/d') }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($product->TimeStart)->format('H:i') }}
                                            〜 {{ \Carbon\Carbon::parse($product->TimeEnd)->format('H:i') }}
                                        </td>
                                        <td class="count">{{ $product->capacity }}</td>
                                        <td class=""> {{ number_format($product->price) }}円</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
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
                                            <a href="{{ route('user_products.edit', $product->id) }}" class="link-button">編集</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>




            <a href="{{ route('user_products.create') }}" class="link-button">
                商品登録
            </a>


        </div>
    </div>
    
</x-app-layout>
