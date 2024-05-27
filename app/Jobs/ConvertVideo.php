<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use FFMpeg\Coordinate\Dimension;
use FFMpeg;
use FFMpeg\Format\Video\X264;

class ConvertVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $video;
    public function __construct($video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $format_240 = (new X264('aac', 'libx264'))->setKiloBitrate(500);
        $format_360 = (new X264('aac', 'libx264'))->setKiloBitrate(900);
        $format_480 = (new X264('aac', 'libx264'))->setKiloBitrate(1500);
        $format_720 = (new X264('aac', 'libx264'))->setKiloBitrate(3000);

        $path_240 = '240-' . $this->video->video_path;
        $path_360 = '360-' . $this->video->video_path;
        $path_480 = '480-' . $this->video->video_path;
        $path_720 = '720-' . $this->video->video_path;

        FFMpeg::fromDisk($this->video->disk)
            ->open($this->video->video_path)
            ->addFilter(function ($filters) {
                $filters->resize(new Dimension(426, 240));
            })
            ->export()
            ->toDisk('public')
            ->inFormat($format_240)
            ->save($path_240)

            ->addFilter(function ($filters) {
                $filters->resize(new Dimension(640, 360));
            })
            ->export()
            ->toDisk('public')
            ->inFormat($format_360)
            ->save($path_360)

            ->addFilter(function ($filters) {
                $filters->resize(new Dimension(854, 480));
            })
            ->export()
            ->toDisk('public')
            ->inFormat($format_480)
            ->save($path_480)

            ->addFilter(function ($filters) {
                $filters->resize(new Dimension(1280, 720));
            })
            ->export()
            ->toDisk('public')
            ->inFormat($format_720)
            ->save($path_720);
    }
}
