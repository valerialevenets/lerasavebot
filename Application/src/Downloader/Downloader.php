<?php

namespace Application\Downloader;
use Telegram\Bot\FileUpload\InputFile;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

class Downloader
{
    public function __construct(private YoutubeDl $youtubeDl)
    {
    }

    public function download(string $url): string
    {
        $tmpname = time();
        $tmp = $this->youtubeDl->download(
            Options::create()
                ->downloadPath('/tmp/instagram')
                ->output($tmpname)
                ->url($url)
                ->cookies(getcwd().'/Application/data/igram.txt')
        );
        foreach ($tmp->getVideos() as $item) {
            if (! $item->getError()) {
                $path = $item->getFile()->getPath();
                $file = $item->getFile()->getFilename();
                return $path.'/'.$file;
            } else {
                throw new \Exception('Download failed');
            }
        }
    }
}