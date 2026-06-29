@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex items-center justify-between">
        {{-- Mobile View --}}
        <div class="flex justify-between flex-1 sm:hidden">
            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 cursor-not-allowed rounded-md">
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white border border-blue-300 rounded-md hover:bg-blue-50 focus:outline-none focus:ring ring-blue-300 active:bg-blue-100 transition ease-in-out duration-150">
                    Sebelumnya
                </a>
            @endif

            {{-- Tombol Berikutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-blue-600 bg-white border border-blue-300 rounded-md hover:bg-blue-50 focus:outline-none focus:ring ring-blue-300 active:bg-blue-100 transition ease-in-out duration-150">
                    Berikutnya
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 cursor-not-allowed rounded-md">
                    Berikutnya
                </span>
            @endif
        </div>

        {{-- Desktop View --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between w-full">
            {{-- Kiri: Teks info --}}
            <div class="flex-1 text-left">
                <p class="text-sm text-gray-700 leading-5">
                    Menampilkan
                    @if ($paginator->firstItem())
                        <span class="font-semibold text-blue-600">{{ $paginator->firstItem() }}</span>
                        sampai
                        <span class="font-semibold text-blue-600">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    dari
                    <span class="font-semibold text-blue-600">{{ $paginator->total() }}</span>
                    data
                </p>
            </div>

            {{-- Kanan: Pagination link --}}
            <div class="flex-1 text-right">
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    {{-- Tombol Sebelumnya --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Halaman Sebelumnya">
                            <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 cursor-not-allowed rounded-l-md">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                           class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-blue-600 bg-white border border-blue-300 rounded-l-md hover:bg-blue-50 focus:z-10 focus:outline-none focus:ring ring-blue-300 active:bg-blue-100 transition ease-in-out duration-150"
                           aria-label="Halaman Sebelumnya">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Nomor Halaman --}}
                    @foreach ($elements as $element)
                        {{-- Separator (...) --}}
                        @if (is_string($element))
                            <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-gray-100 border border-gray-300 cursor-default">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Nomor Link --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-semibold text-white bg-blue-600 border border-blue-600 cursor-default rounded-md">
                                            {{ $page }}
                                        </span>
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                       class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-600 bg-white border border-blue-300 rounded-md hover:bg-blue-50 focus:z-10 focus:outline-none focus:ring ring-blue-300 active:bg-blue-100 transition ease-in-out duration-150"
                                       aria-label="Pergi ke halaman {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Tombol Berikutnya --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                           class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-blue-600 bg-white border border-blue-300 rounded-r-md hover:bg-blue-50 focus:z-10 focus:outline-none focus:ring ring-blue-300 active:bg-blue-100 transition ease-in-out duration-150"
                           aria-label="Halaman Berikutnya">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Halaman Berikutnya">
                            <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 cursor-not-allowed rounded-r-md">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif