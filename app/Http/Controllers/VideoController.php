<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
            'image_path' => 'required|image',
            'video_path' => 'required',
        ]);

        $reandomPath = Str::random(16);
        $videoPath = $reandomPath . '.'  . $request->video->getClientOriginalExtension();
        $imagePath = $reandomPath . '.'  . $request->image->getClientOriginalExtension();

        $image = Image::make($request->image)->resize(320, 180);
        $path = Storage::put($imagePath, $image->stream());
        $request->video->storeAs('/', $videoPath, 'public');

        Video::create([
            'disk' => 'public',
            'title' =>  $request->title,
            'image' => $imagePath,
            'video' => $videoPath,
            'user_id' => auth()->id(),
        ]);
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
