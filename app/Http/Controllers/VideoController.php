<?php

namespace App\Http\Controllers;

use App\Models\Video;
use FFMpeg\Coordinate\Dimension;
use FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('videos.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' =>  'required',
            'image' => 'required|image',
            'video' => 'required',
        ]);

        $reandomPath = time();
        $videoPath = $reandomPath . '.'  . $request->video->getClientOriginalExtension();
        $imagePath = $reandomPath . '.'  . $request->image->getClientOriginalExtension();

        $image = Image::make($request->image)->resize(320, 180);
        $path = Storage::put($imagePath, $image->stream());
        $request->video->storeAs('/', $videoPath, 'public');

        $video =  Video::create([
            'disk' => 'public',
            'title' =>  $request->title,
            'image_path' => $imagePath,
            'video_path' => $videoPath,
            'user_id' => auth()->id(),
        ]);
        $format_240 = (new X264('aac', 'libx264'))->setKiloBitrate(500);
        $format_360 = (new X264('aac', 'libx264'))->setKiloBitrate(900);
        $format_480 = (new X264('aac', 'libx264'))->setKiloBitrate(1500);
        $format_720 = (new X264('aac', 'libx264'))->setKiloBitrate(3000);

        $path_240 = '240-' . $video->video_path;
        $path_360 = '360-' . $video->video_path;
        $path_480 = '480-' . $video->video_path;
        $path_720 = '720-' . $video->video_path;

        FFMpeg::fromDisk($video->disk)
            ->open($video->video_path)
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

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
