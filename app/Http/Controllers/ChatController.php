<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use App\Events\ChatMessageRecieved;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use app\Models\User;

class ChatController extends Controller
{
    public function __construct()
    {
    }
 
    public function add(Request $request)
    {
        $user = Auth::user();
        $comment = $request->input('comment');
        chat::create([
            'login_id' => $user->id,
            'name' => $user->name,
            'comment' => $comment
        ]);
        return redirect()->route('chat.index');
    }
    public function index(Request $request, $recieve)
    {
        // チャットの画面
        $loginId = Auth::id();
 
        $param = [
          'send' => $loginId,
          'recieve' => $recieve,
        ];
 
        // 送信 / 受信のメッセージを取得する
        $query = chat::where('send' , $loginId)->where('recieve' , $recieve);;
        $query->orWhere(function($query) use($loginId , $recieve){
            $query->where('send' , $recieve);
            $query->where('recieve' , $loginId);
 
        });
 
        $messages = $query->get();
 
        return view('chat.chat' , compact('param' , 'messages'));
    }

    public function store(Request $request)
    {
        // リクエストパラメータ取得
        $insertParam = [
            'send' => $request->input('send'),
            'recieve' => $request->input('recieve'),
            'message' => $request->input('message'),
        ];
 
 
        // メッセージデータ保存
        try{
            chat::insert($insertParam);
        }catch (\Exception $e){
            return false;
 
        }
 
 
        // イベント発行
        event(new ChatMessageRecieved($request->all()));
 
        // メール送信
        //$mailSendUser = User::where('id' , $request->input('recieve'))->first();
        //$to = $mailSendUser->email;
        //Mail::to($to)->send(new SampleNotification());
 
        return true;
    }
    public function getData()
    {
        $comments = chat::orderBy('created_at', 'desc')->get();
        $json = ["comments" => $comments];
        return response()->json($json);
    }
}
