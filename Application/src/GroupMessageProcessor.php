<?php

namespace Application;

use Application\Downloader\Downloader;
use Application\ValueObject\PublicResponses;
use Application\ValueObject\SerhiiResponses;
use Application\ValueObject\Triggers;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Objects\File;
use Telegram\Bot\Objects\Message;

class GroupMessageProcessor
{
    private Api $telegram;
    private Downloader $downloader;
    public function __construct(Api $telegram, Downloader $downloader)
    {
        $this->telegram = $telegram;
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