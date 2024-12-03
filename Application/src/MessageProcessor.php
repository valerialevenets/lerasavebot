<?php

namespace Application;

use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message;

class MessageProcessor
{
    private Api $telegram;
    private PrivateChatProcessor $privateChatProcessor;
    private GroupMessageProcessor $groupMessageProcessor;

    public function __construct(
        Api $telegram,
        PrivateChatProcessor $privateChatProcessor,
        GroupMessageProcessor $groupMessageProcessor
    ) {
        $this->telegram = $telegram;
        $this->privateChatProcessor = $privateChatProcessor;
        $this->groupMessageProcessor = $groupMessageProcessor;
    }

    public function process(Message $message)
    {
        switch ($message->chat->type) {
            case 'private':
                $this->privateChatProcessor->processMessage($message);
                break;
            case 'group':
            case 'supergroup':
                $this->groupMessageProcessor->processMessage($message);
                break;
        }
    }
}