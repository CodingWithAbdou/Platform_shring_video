<?php

namespace App\Http\Controllers;

use App\Jobs\ConvertVideo;
use App\Models\ConvertedVideo;
use App\Models\Video;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class VideoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'جميع الفيديوات التي قمت برفعها';
        $videos = auth()->user()->videos()->where('processed', true)->paginate(12);
        return view('videos.index', compact('videos', 'title'));
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

        $image = Image::make($request->image)->resize(480, 260);
        $path = Storage::put($imagePath, $image->stream());
        $request->video->storeAs('/', $videoPath, 'public');

        $video =  Video::create([
            'disk' => 'public',
            'title' =>  $request->title,
            'image_path' => $imagePath,
            'video_path' => $videoPath,
            'user_id' => auth()->id(),
        ]);

        ConvertVideo::dispatch($video);

        return redirect()->back()->with('success', 'تتم معالجة مقطع الفيديو سيصلك إشعار عند  إنتهاء المعالجة');
    }

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        return view('videos.show', compact('video'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $video = Video::where('id', $id)->first();
        return view('videos.edit', compact('video'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'title' =>  'required',
            'image' => 'image',
        ]);

        $video = Video::findOrFail($id);

        if ($request->hasFile('image')) {
            $reandomPath = time();
            $imagePath = $reandomPath . '.'  . $request->image->getClientOriginalExtension();
            $image = Image::make($request->image)->resize(480, 260);
            $path = Storage::put($imagePath, $image->stream());
            Storage::delete($video->image_path);
            $video->image_path = $imagePath;
        }

        $video->title = $request->title;
        $video->save();

        return redirect(route('videos.index'))->with('success', 'تم تحديث مقطع الفيديو بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $video = Video::where('id', $id)->first();
        $convertedVideos = ConvertedVideo::where('video_id', $id)->get();

        foreach ($convertedVideos as $covertedVideo) {
            Storage::delete([
                $covertedVideo->mp4_Format_240,
                $covertedVideo->mp4_Format_360,
                $covertedVideo->mp4_Format_480,
                $covertedVideo->mp4_Format_720,
                $covertedVideo->mp4_Format_1080,
                $covertedVideo->webm_Format_240,
                $covertedVideo->webm_Format_360,
                $covertedVideo->webm_Format_480,
                $covertedVideo->webm_Format_720,
                $covertedVideo->webm_Format_1080,
                $video->image_path
            ]);
        }

        $video->delete();

        return back()->with('success', 'تم حذف مقطع الفيديو بنجاح');
    }


    public function search(Request $request)
    {
        $title = 'نتائج البحث عن :' . $request->search;
        $videos = auth()->user()->videos()->where('title', 'like', '%' . $request->search . '%')->paginate(12);
        return view('videos.index', compact('videos', 'title'));
    }
}
