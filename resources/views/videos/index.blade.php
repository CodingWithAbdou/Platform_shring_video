@push('style')
@endpush
@push('script')
    <script>
        $(document).ready(function() {
            $('.menu-button').on('click', function() {
                $(this).next().toggleClass('hidden');
            })
        });
    </script>
@endpush

<x-app-layout>
    <div class="max-w-screen-xl mx-auto p-5 sm:p-10 md:p-16">
        <div class="mb-8">
            <form action="{{ route('videos.search') }}" method="POST" class="mb-4">
                @csrf
                @method('POST')
                <div class="flex items-center mx-auto w-fit">
                    <input type="text" name="search" placeholder="ابحث عن فيديوهات"
                        class="rounded-r-lg py-2 w-[400px]  px-4 border-t border-b border-r text-gray-800 border-gray-200 bg-white focus:outline-none">
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-l-lg hover:bg-indigo-500 focus:outline-none">بحث</button>
                </div>
            </form>
            <h2 class="my-8 mt-12 text-neutral-800">{{ $title }}</h2>
            <hr>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 sm:grid-cols-2 gap-10">
            @forelse ($videos as $video)
                <div class="rounded overflow-hidden shadow-lg min-h-[200px]">
                    <div class="relative">
                        <a href="{{ route('videos.show', $video->id) }}">
                            <img class="w-full" src="{{ Storage::url($video->image_path) }}" alt="{{ $video->tilte }}">
                            <div
                                class="hover:bg-transparent transition duration-300 absolute bottom-0 top-0 right-0 left-0 bg-gray-900 opacity-25">
                            </div>
                        </a>
                        @php
                            $hours_add_zero = sprintf('%02d', $video->hours);
                        @endphp
                        @php
                            $minutes_add_zero = sprintf('%02d', $video->minutes);
                        @endphp
                        @php
                            $seconds_add_zero = sprintf('%02d', $video->seconds);
                        @endphp
                        <a href="#!">
                            <div
                                class="absolute bottom-2 left-2 bg-indigo-600 px-2 py-1 text-white text-xs hover:bg-white hover:text-indigo-600 transition duration-500 ease-in-out">
                                {{ $video->hours > 0 ? $hours_add_zero . ':' : '' . $minutes_add_zero . ':' . $seconds_add_zero }}
                            </div>
                        </a>
                        @auth
                            @if (auth()->id() == $video->user_id || auth()->user()->administration_level > 0)
                                <div class="absolute top-2 left-2 inline-block text-right">
                                    <button type="button"
                                        class="menu-button inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                                        aria-expanded="true" aria-haspopup="true">
                                        <i class='bx bx-dots-vertical-rounded'></i>
                                    </button>

                                    <div class="drop-down hidden absolute left-0 z-10 mt-1 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                        role="menu" aria-orientation="vertical" aria-labelledby="menu-button"
                                        tabindex="-1">
                                        <div class="py-1" role="none">
                                            <form action="{{ route('videos.edit', $video->id) }}" role="none">
                                                @csrf
                                                <button type="submit"
                                                    class="text-gray-700 block w-full px-4 py-2 text-right text-sm"
                                                    role="menuitem" tabindex="-1" id="menu-item-3">تعديل</button>
                                            </form>
                                            <form method="POST" action="{{ route('videos.destroy', $video->id) }}"
                                                role="none">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-gray-700 block w-full px-4 py-2 text-right text-sm"
                                                    role="menuitem" tabindex="-1" id="menu-item-3">حذف</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endauth

                    </div>
                    <div class="px-6 pt-2">

                        <a href="{{ route('videos.show', $video->id) }}"
                            class="font-semibold text-lg inline-block hover:text-indigo-600 transition duration-500 ease-in-out">{{ $video->title }}</a>
                    </div>
                    <div class="px-6 pt-2 pb-2 flex flex-row items-center justify-between">
                        <span class="py-1 text-sm font-regular text-gray-900 mr-1 flex flex-row items-center">
                            <i class='bx bx-time-five me-2 mt-1'></i>
                            <span class="ml-1 text-sm">منذ 6 دقائق </span>
                        </span>
                        <span class="py-1 text-sm font-regular text-gray-900 mr-1 flex flex-row items-center">
                            <i class='bx bx-show-alt m-2 mt-2'></i>
                            <span class="ml-1">60</span>
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-3   max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 my-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 w-full"
                    role="alert">
                    <span class="font-medium">
                        لايوجد فيديوات لعرضها
                    </span>
                </div>
            @endforelse
            <div class="col-span-3">
                {{ $videos->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
