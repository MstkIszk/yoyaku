<style>
    .head_frame {
        position:relative;  /* 相対的位置指定。要素の元の位置を基準にして、top、right、bottom、leftプロパティで移動可 */
        background-image: url('{{ asset('images/slide_back.jpg') }}');
        background-repeat: repeat;
    }
    .head_inbox {
        position:relative;
        margin:0 auto;
        overflow:hidden;
        width:1000px;
        height:200px;

    }
    .head_image {
        display: block; 
        position: absolute; 
        min-width:400px;
        max-width:1000px;
        min-height:200px;
        max-height:400px;
        top: 2px;
    }
    .textbar {
        position: relative;
        top: 5px;
        left: 5px;                    
        width:90%;
        font-size: 2rem;
        opacity: 50%;
        background-color: #C08880;
        color: #337079;
        text-align: center;
    }                
    .textbox {
        color: #000000
        ;
        opacity: 100%;
    }                
</style>
<!--
app.blade.php<br>
└「」
└「resources\views\components\application-logo.blade.php」--- start
<p>logoのフォルダ: {{ getcwd() }}</p>
<p>url path: {{ asset('images/backimage1.jpeg') }} </p>
<p>helper path: {{ getImagePath() }} </p-->


<div class="head_frame">
    <div class="head_inbox">
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
            <text class="textbox" id="shopname" text-anchor="middle" font-size="20px">{{ __('Resavation guide') }}</text>
        @endisset

 
        </div>
    </div>
</div>
<!--「resources\views\components\application-logo.blade.php」--- end -->

