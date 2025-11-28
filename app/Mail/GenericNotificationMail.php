<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class GenericNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * メール本文として使用する内容を保持するパブリックプロパティ。
     * Bladeテンプレート内で $mailContent としてアクセスできます。
     *
     * @var string
     */
    public $mailContent;
    
    // ★ Bccアドレスを保存するためのプライベートプロパティ
    private array $bccAddresses = []; 

    /**
     * メール送信のための新しいメッセージインスタンスを作成します。
     * * @param string $toAddress 宛先メールアドレス（カンマ区切り可）
     * @param string $subjectText 件名
     * @param string $content メール本文
     * @param string $fromAddress 差出人メールアドレス
     * @param string $fromName 差出人名
     * @param string $toName 宛先名（デフォルトは空）
     * @param array $bcc Bccアドレスの配列
     * @return void
     */
    public function __construct(
        private string $toAddress, 
        private string $subjectText,
        string $content,
        private string $fromAddress, 
        private string $fromName,  
        private string $toName = '',
        array $bcc = [] // Bccアドレスの配列を受け取る
    ) {
        // コンストラクタで受け取った本文をパブリックプロパティに格納
        $this->mailContent = $content;
        // Bccアドレスを保存
        $this->bccAddresses = $bcc;
    }

    /**
     * メッセージエンベロープ（宛先、件名、差出人など）を取得します。
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        // 宛先アドレスをカンマで分割し、Addressオブジェクトの配列に変換
        $recipients = collect(explode(',', $this->toAddress))
            ->map(fn($email) => trim($email))
            ->filter()
            ->unique()
            ->map(fn($email) => new Address($email, $this->toName));

        // BccアドレスをAddressオブジェクトの配列に変換
        $bcc = collect($this->bccAddresses)
            ->filter()
            ->unique()
            ->map(fn($email) => new Address($email));

        // Return-PathとFromを設定
        $from = new Address($this->fromAddress, $this->fromName);

        return new Envelope(
            to: $recipients->all(),        // 宛先配列
            subject: $this->subjectText,   // 件名
            from: $from,                   // 差出人Fromを設定
            replyTo: [$from],              // 返信先Reply-Toを設定
            bcc: $bcc->all(),              // ★ Bccを設定
        );
    }

    /**
     * メッセージコンテンツ定義（本文）を取得します。
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            // ★ 指定されたプレーンテキストビューを使用
            // この場合、resources/views/emails/generic-notification-text.blade.php が必要です。
            text: 'emails.generic-notification-text',
            
            // パブリックプロパティ ($this->mailContent) は自動的にビューに渡されます
        );
    }

    /**
     * メッセージの添付ファイルを取得します。
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
