@push('style')
@endpush
@push('script')
    <script>
        const dropzoneImage = document.getElementById('dropzone-img');
        const dropzoneVideo = document.getElementById('dropzone-video');

        dropzoneImage.addEventListener('dragover', e => {
            e.preventDefault();
            dropzoneImage.classList.add('border-indigo-600');
        });

        dropzoneImage.addEventListener('dragleave', e => {
            e.preventDefault();
            dropzoneImage.classList.remove('border-indigo-600');
        });

        dropzoneVideo.addEventListener('dragover', e => {
            e.preventDefault();
            dropzoneVideo.classList.add('border-indigo-600');
        });

        dropzoneVideo.addEventListener('dragleave', e => {
            e.preventDefault();
            dropzoneVideo.classList.remove('border-indigo-600');
        });

        dropzoneImage.addEventListener('drop', e => {
            e.preventDefault();
            dropzoneImage.classList.remove('border-indigo-600');
            const file = e.dataTransfer.files[0];
            displayPreviewImg(file);
        });

        dropzoneVideo.addEventListener('drop', e => {
            e.preventDefault();
            dropzoneVideo.classList.remove('border-indigo-600');
            const file = e.dataTransfer.files[0];
            displayPreviewVideo(file)
        });

        const inputImage = document.getElementById('image-upload');

        inputImage.addEventListener('change', e => {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                displayPreviewImg(file);
            }
        });

        const inputVideo = document.getElementById('video-upload');

        inputVideo.addEventListener('change', e => {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                displayPreviewVideo(file);
            }
        });

        function displayPreviewImg(file) {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                const preview = document.getElementById('preview');
                preview.src = reader.result;
                preview.classList.remove('hidden');
            };
        }

        function displayPreviewVideo(file) {
            const reader = new FileReader();
            console.log(reader)
            reader.readAsDataURL(file);
            reader.onload = () => {
                const preview = document.getElementById('video-name');
                console.log(file)
                preview.innerHTML = ` تم رفع الفيديو <span  class="text-indigo-700"> ${file.name} </span>`;
            };
        }
    </script>
@endpush

<x-app-layout>
    <div class="p-4 flex flex-col	 items-center justify-center direc">

        <h2 class="w-full bg-gray-100 rounded-md shadow-sm p-4 text-center ">عرض الفيديو</h2>
        <div class=" mt-8">
            <div>
                @foreach ($video->convertedvideos as $video_converted)
                    <video id="videoPlayer" controls
                        class="shadow-lg rounded-lg {{ $video->Longitudinal == '0' ? 'w-auto h-auto' : 'w-[900px] h-[510px] ' }}">
                        @if ($video->quality == 1080)
                            <source id="webm_source" src="{{ Storage::url($video_converted->webm_Format_1080) }}"
                                type="video/webm">
                            <source id="mp4_source" src="{{ Storage::url($video_converted->mp4_Format_1080) }}"
                                type="video/mp4">
                        @elseif($video->quality == 720)
                            <source id="webm_source" src="{{ Storage::url($video_converted->webm_Format_720) }}"
                                type="video/webm">
                            <source id="mp4_source" src="{{ Storage::url($video_converted->mp4_Format_720) }}"
                                type="video/mp4">
                        @elseif($video->quality == 480)
                            <source id="webm_source" src="{{ Storage::url($video_converted->webm_Format_480) }}"
                                type="video/webm">
                            <source id="mp4_source" src="{{ Storage::url($video_converted->mp4_Format_480) }}"
                                type="video/mp4">
                        @elseif($video->quality == 360)
                            <source id="webm_source" src="{{ Storage::url($video_converted->webm_Format_360) }}"
                                type="video/webm">
                            <source id="mp4_source" src="{{ Storage::url($video_converted->mp4_Format_360) }}"
                                type="video/mp4">
                        @else
                            <source id="webm_source" src="{{ Storage::url($video_converted->webm_Format_240) }}"
                                type="video/webm">
                            <source id="mp4_source" src="{{ Storage::url($video_converted->mp4_Format_240) }}"
                                type="video/mp4">
                        @endif
                    </video>
                @endforeach
            </div>
            <div class="relative inline-flex w-fit">
                <select id='qualityPick' name="quality"
                    class="block appearance-none w-full bg-white border border-gray-300 hover:border-gray-400 px-4 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                    <option value="1080" {{ $video->quality == 1080 ? 'selected' : '' }}
                        {{ $video->quality < 1080 ? 'hidden' : '' }}>
                        1080p</option>
                    <option value="720" {{ $video->quality == 720 ? 'selected' : '' }}
                        {{ $video->quality < 720 ? 'hidden' : '' }}>
                        720p</option>
                    <option value="480" {{ $video->quality == 480 ? 'selected' : '' }}
                        {{ $video->quality < 480 ? 'hidden' : '' }}>
                        480p</option>
                    <option value="360" {{ $video->quality == 360 ? 'selected' : '' }}
                        {{ $video->quality < 360 ? 'hidden' : '' }}>
                        360p</option>
                    <option value="240" {{ $video->quality == 240 ? 'selected' : '' }}>240p</option>
                </select>
            </div>
            <h2 class="mt-4 text-xl text-neutral-800">{{ $video->title }}</h2>
        </div>
    </div>
</x-app-layout>
