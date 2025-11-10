<!--
app.blade.php<br>
└「」
└「resources\views\components\application-logo.blade.php」--- start
<p>logoのフォルダ: {{ getcwd() }}</p>
<p>url path: {{ asset('images/backimage1.jpeg') }} </p>
<p>helper path: {{ getImagePath() }} </p-->


<div class="head_frame">
    <div class="head_inbox">
        <div class="head_image_wrap">            
        <!-- img class="head_image" src="{{ asset('images\backimage1.jpg') }}" alt="トップ画像わ" -->
            <!--img class="head_image" src="..\..\..\resources\images\backimage1.jpeg" alt="トップ画像"-->
            <img class="head_image" src="{{ asset('images/backimage1.jpeg') }}" alt="トップ画像">
            <div class="textbar">
            
            @isset($ShopInf)
                <text class="textbox" id="shopname" text-anchor="middle" font-size="20px">{{ $ShopInf->spName }}</text>
                @auth
                    @if(Auth::user()->id == $ShopInf->id)
                        <text class="textbox" id="myText" x="50%" y="50%" text-anchor="middle" font-size="20px">{{ __('Dashboard') }}</text>
                    @endif
                @endauth
            @else
                @auth
                    <text class="textbox" id="shopname" text-anchor="middle" font-size="20px">{{ Auth::user()->spName }}</text>
                @else    
                    <text class="textbox" id="shopname" text-anchor="middle" font-size="20px">{{ __('Resavation guide') }}</text>
                @endauth
            @endisset

    
            </div>
        </div>
    </div>
</div>
<!--「resources\views\components\application-logo.blade.php」--- end -->

