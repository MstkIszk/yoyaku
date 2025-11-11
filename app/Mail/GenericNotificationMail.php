<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;


class GenericNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    
    /**
     * メール本文として使用する内容を保持するパブリックプロパティ。
     * Bladeテンプレート内で $content としてアクセスできます。
     *
     * @var string
     */
    public $mailContent;

    /**
     * メール送信のための新しいメッセージインスタンスを作成します。
     * * @param string $toAddress 宛先メールアドレス
     * @param string $subject 件名
     * @param string $content メール本文
     * @return void
     */
    public function __construct(
        private string $toAddress, // 宛先アドレスをプライベートプロパティとして保持
        private string $subjectText, // 件名テキストをプライベートプロパティとして保持
        string $content,
        private string $fromAddress, 
        private string $fromName,    
        private string $toName = '' // ★ 宛先名を追加（デフォルトは空）
    ) {
        // コンストラクタで受け取った本文をパブリックプロパティに格納
        // これにより、ビュー（Blade）でアクセス可能になります。
        $this->mailContent = $content;
    }

    /**
     * メッセージエンベロープ（宛先、件名など）を取得します。
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        // 宛先アドレスをカンマで分割し、配列として処理
        // EnvelopeのtoにはAddressオブジェクトの配列が必要です
        $recipients = collect(explode(',', $this->toAddress))
            ->map(fn($email) => trim($email))
            ->filter()
            ->unique()
            ->map(fn($email) => new Address($email, $this->toName));

        // Return-PathとFromを同じアドレスに設定（元のmb_send_mailの動作を再現）
        $from = new Address($this->fromAddress, $this->fromName);

        return new Envelope(
            to: $recipients->all(),         // 宛先配列
            subject: $this->subjectText,    // 件名
            from: $from,                    // 差出人Fromを設定
            replyTo: [$from],               // 返信先Reply-Toを設定
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // Markdownビューを使用する場合
            markdown: 'emails.generic-notification',
            // または、通常のBladeビューを使用する場合
            // view: 'emails.generic-notification', 
            // `with`を使用してビューにデータを渡すこともできますが、
            // 今回はパブリックプロパティ ($this->mailContent) で渡します。
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
