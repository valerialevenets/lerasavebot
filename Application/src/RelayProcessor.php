<?php

namespace Application;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message;

class RelayProcessor
{
    private Api $telegram;

    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }
    public function relay(Message $message): void
    {
        if (
            in_array(
                $message->chat->username,
                ['askafeynman', 'aGVoZSBwZW5pcw']
            )
            && $this->hasUrl($message->text)
        ) {
            $ids = $this->getIds($message->text);
            $this->telegram->sendMessage([
                'chat_id' => (int) '-100'.$ids['group_id'],
                'reply_to_message_id' => $ids['message_id'],
                'text' => str_replace(
                    ['/reply', $this->getUrl($message->text)],'', $message->text
                )
            ]);
        }
    }

    private function getIds(string $message): array
    {
        $tmp = array_reverse(explode('/', $this->getUrl($message)));
        return [
            'message_id' => $tmp[0],
            'group_id' => $tmp[1],
        ];
    }

    private function extractUrls(string $string)
    {
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $string, $match);

        return $match;
    }

    private function getUrl(string $string): ?string
    {
        $urls = $this->extractUrls($string);
        if (!empty($urls)) {
            return $urls[0][0];
        }
        return null;
    }

    private function hasUrl(string $string): bool
    {
        return !empty($this->extractUrls($string));
    }
}