<?php

namespace Application;

use Application\Downloader\Downloader;
use CURLFile;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Objects\Message;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

class PrivateChatProcessor
{
    private Api $telegram;
    private RelayProcessor $relay;
    private Downloader $downloader;

    public function __construct(Api $telegram, RelayProcessor $relay, Downloader $downloader)
    {
        $this->telegram = $telegram;
        $this->relay = $relay;
        $this->downloader = $downloader;
    }

    public function processMessage(Message $message)
    {
        $messageContent = $message->text;
        $url = parse_url($messageContent);
        if (isset($url['host']) && in_array($url['host'], ['instagram.com', 'www.instagram.com'])) {
            $file = $this->downloader->download($messageContent);
                $this->telegram->sendVideo(
                    [
                        'chat_id' => $message->chat->id,
                        'reply_to_message_id' => $message->get('message_id'),
                        'video' => InputFile::createFromContents(file_get_contents($file), 'video.mp4'),
                    ]
                );
                unlink($file);
        }
    }
}