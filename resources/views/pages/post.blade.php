@extends('components.tailwindcss.templates.pages')

@section('content')

    <div>
        <div class="container mx-auto mb-10">
            <div class="pt-16 lg:pt-20">
                <div class="border-b border-grey-lighter pb-8 sm:pb-12">

                    @php
                        $mapBackgroundAndTextColor = [
                            'bg-green-light text-green',
                            'bg-grey-light text-blue-dark',
                            'bg-yellow-light text-yellow-dark',
                            'bg-blue-light text-blue'
                        ];
                    @endphp
                    @if($post->categories->isNotEmpty())

                        <div class="flex flex-wrap gap-2 mb-3">

                            @forelse($post->categories as $category)

                                <span
                                    class="inline-block rounded-full px-2 py-1 font-body text-sm {{ $mapBackgroundAndTextColor[rand(0, count($mapBackgroundAndTextColor) - 1)] }}">{{ $category->title ?? '' }}</span>

                            @empty @endforelse

                        </div>

                    @endif

                    <h2 class="block font-body text-3xl font-semibold leading-tight text-primary dark:text-white sm:text-4xl md:text-5xl">
                        {{ $post->title ?? '' }}
                    </h2>
                    <div class="flex items-center pt-5 sm:pt-8">
                        <p class="pr-2 font-body font-light text-primary dark:text-white">{{ ($post->created_at ?? null) ? $post->created_at->format('F d, Y') : '' }}</p>
                    </div>
                </div>
                <div class="prose max-w-none border-b border-grey-lighter py-8 dark:prose-dark sm:py-12">
                    {!!  $post->content ?? '' !!}
                </div>
                <div class="flex items-center py-10">
                    <span class="pr-5 font-body font-medium text-primary dark:text-white">Share</span>
                    <a href="/">
                        <i class="bx bxl-facebook text-2xl text-primary transition-colors hover:text-secondary dark:text-white dark:hover:text-secondary"></i>
                    </a>
                    <a href="/">
                        <i class="bx bxl-twitter pl-2 text-2xl text-primary transition-colors hover:text-secondary dark:text-white dark:hover:text-secondary"></i>
                    </a>
                    <a href="/">
                        <i class="bx bxl-linkedin pl-2 text-2xl text-primary transition-colors hover:text-secondary dark:text-white dark:hover:text-secondary"></i>
                    </a>
                    <a href="/">
                        <i class="bx bxl-reddit pl-2 text-2xl text-primary transition-colors hover:text-secondary dark:text-white dark:hover:text-secondary"></i>
                    </a>
                </div>
                <nav role="navigation" aria-label="Pagination Navigation"
                     class="flex justify-between flex-col md:flex-row gap-3">

                    @if($previous)

                        <div
                            class="bg-white border border-gray-300 rounded-md focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 px-4 py-2 transition ease-in-out duration-150">
                            <a href="{{ route('pages.post', ($previous->slug ?? null)) }}" rel="previous"
                               class="text-ellipsis line-clamp-2 text-sm font-medium text-gray-700 leading-5 hover:text-gray-500 active:bg-gray-100 active:text-gray-700">
                                {{ $previous->title ?? '' }}
                            </a>
                        </div>

                    @else

                        <div></div>

                    @endif
                    @if($next)

                        <div
                            class="bg-white border border-gray-300 rounded-md focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 px-4 py-2 transition ease-in-out duration-150">
                            <a href="{{ route('pages.post', ($next->slug ?? null)) }}" rel="next"
                               class="text-ellipsis line-clamp-2 text-sm font-medium text-gray-700 leading-5 hover:text-gray-500 active:bg-gray-100 active:text-gray-700">
                                {{ $next->title ?? '' }}
                            </a>
                        </div>

                    @endif

                </nav>
            </div>
        </div>
    </div>

@endsection
