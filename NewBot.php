<?php

exec('composer update');
require __DIR__.'/vendor/autoload.php';

use Application\MessageProcessor;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;

class NewBot
{
    public function __construct(
        private Api $telegram,
        private int $latestUpdateId,
        private MessageProcessor $messageProcessor,
    ) {
        $this->telegram = $telegram;
        $this->messageProcessor = $messageProcessor;
        $this->latestUpdateId = $latestUpdateId;
    }

    public function getLatestUpdateId(): int
    {
        return $this->latestUpdateId;
    }
    public function process(): void
    {
        $this->processUpdates($this->telegram->getUpdates(['offset' => $this->getLatestUpdateId(), 'allowed_updates' => ['message']]));
    }
    private function processUpdates(array $updates):void {
        foreach($updates as $update) {
            try{
                $this->processSingleUpdate($update);
            } catch (\Exception $ex) {
                //yes, do nothing but log
                throw $ex;
            }
        }
    }

    function processSingleUpdate(Update $update):void {
        $this->latestUpdateId = $update->get('update_id')+1;
        $message = $update->getMessage();
        if (! $message instanceof Message) {
            return;
        }
        $this->messageProcessor->process($message);
    }
}

$downloader = new \Application\Downloader\Downloader(new \YoutubeDl\YoutubeDl());
$tg = new Api(\Application\ConfigProvider::getTelegramToken());
$bot = new NewBot(
    $tg,
    1,
    new MessageProcessor(
        $tg,
        new \Application\PrivateChatProcessor(
            $tg,
            new \Application\RelayProcessor($tg),
            $downloader
        ),
        new \Application\GroupMessageProcessor($tg, $downloader)
    )
);
$id = $bot->getLatestUpdateId();
for(;;) {
    $bot->process();
    sleep(1);
}