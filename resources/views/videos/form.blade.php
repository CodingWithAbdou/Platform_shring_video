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

        <h2 class="w-full bg-gray-100 rounded-md shadow-sm p-4 text-center ">رفع فيدو على المنصة</h2>
        <form class="flex flex-col gap-4" action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mt-4">
                <label for="error" class="block mb-2 text-sm font-medium text-neutral-700 dark:text-neutral-500">إسم
                    الفيديو</label>
                <input type="text" name="title" id="error"
                    class="bg-neutral-50 border  border-neutral-500 text-neutral-900 placeholder-neutral-700 text-sm rounded-lg focus:ring-neutral-500 dark:bg-gray-700 focus:border-neutral-500 block w-full p-2.5 dark:text-neutral-500 dark:placeholder-neutral-500 dark:border-neutral-500 "
                    value="{{ old('title') }}">
                @error('title')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500"><span class="font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <h4 class="mb-2">صورة
                    الغلاف</h4>
                <div class="w-[400px] relative border-2 border-gray-300 border-dashed rounded-lg p-6" id="dropzone-img">
                    <input type="file" name="image" accept="image/*"
                        class="absolute inset-0 w-full h-full opacity-0 z-50  cursor-pointer" id="image-upload" />
                    <div class="text-center">
                        <img class="mx-auto h-12 w-12" src="https://www.svgrepo.com/show/357902/image-upload.svg"
                            alt="">
                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                            <label for="image-upload" class="relative cursor-pointer">
                                <span>يمكن سحب الصورة لرفعها </span>
                            </label>
                        </h3>
                        <p class="mt-1 text-xs text-gray-500">
                            PNG, JPG, GIF إالى 10MB
                        </p>
                    </div>

                    <img src="" class="mt-4 mx-auto max-h-40 hidden" id="preview">
                </div>
            </div>

            <div>
                <h4 class="mb-2">الفيديو
                </h4>
                <div class="w-[400px] relative border-2 border-gray-300 border-dashed rounded-lg p-6"
                    id="dropzone-video">
                    <input type="file" name="video" accept="video/*"
                        class="absolute inset-0 w-full h-full opacity-0 z-50  cursor-pointer" id="video-upload" />
                    <div class="text-center mb-2">
                        <h3 class="mt-2 text-sm font-medium text-gray-900">
                            <label for="video-upload" class="relative cursor-pointer">
                                <span>يمكن سحب الفيديو لرفعه </span>
                            </label>
                        </h3>
                    </div>
                    <span class=" text-neutral-400" id="video-name"> </span>
                </div>
            </div>

            <div class="mt-2">
                <button
                    class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-green-400 to-blue-600 group-hover:from-green-400 group-hover:to-blue-600 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800">
                    <span
                        class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                        إرســال
                    </span>
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
