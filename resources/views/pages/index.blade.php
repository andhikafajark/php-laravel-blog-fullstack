@extends('components.tailwindcss.templates.pages')

@section('content')

    <div>
        <div class="container mx-auto">
            <div class="py-16 lg:py-20">
                <div>
                    <img src="{{ asset('assets') }}/img/icon-blog.png" alt="icon envelope"/>
                </div>
                <h1 class="pt-5 font-body text-4xl font-semibold text-primary dark:text-white md:text-5xl lg:text-6xl">
                    Blog
                </h1>
                <div class="pt-3 sm:w-3/4">
                    <p class="font-body text-xl font-light text-primary dark:text-white">
                        Articles, tutorials, snippets, rants, and everything else. Subscribe for
                        updates as they happen.
                    </p>
                </div>
                <hr class="border-grey-lighter mt-8">
                <div class="pt-8 lg:pt-12">

                    @php
                        $mapBackgroundAndTextColor = [
                            'bg-green-light text-green',
                            'bg-grey-light text-blue-dark',
                            'bg-yellow-light text-yellow-dark',
                            'bg-blue-light text-blue'
                        ];
                    @endphp
                    @forelse($posts as $post)

                        <div class="border-b border-grey-lighter pb-8 mb-3">

                            @if($post->categories->isNotEmpty())

                                <div class="flex flex-wrap gap-2 mb-3">

                                    @forelse($post->categories as $category)

                                        <span
                                            class="inline-block rounded-full px-2 py-1 font-body text-sm {{ $mapBackgroundAndTextColor[rand(0, count($mapBackgroundAndTextColor) - 1)] }}">{{ $category->title ?? '' }}</span>

                                    @empty @endforelse

                                </div>

                            @endif

                            <a href="{{ route('pages.post', ($post->slug ?? null)) }}"
                               class="block font-body text-lg font-semibold text-primary transition-colors hover:text-green dark:text-white dark:hover:text-secondary">
                                {{ $post->title ?? '' }}
                            </a>
                            <p class="font-body font-light text-primary dark:text-white">{{ $post->subtitle ?? '' }}</p>
                            <div class="flex items-center pt-4">
                                <p class="pr-2 font-body font-light text-primary dark:text-white">
                                    {{ ($post->created_at ?? null) ? $post->created_at->format('F d, Y') : '' }}
                                </p>
                            </div>
                        </div>

                    @empty @endforelse

                </div>

                @if($posts->hasPages())

                    <div class="mt-10">
                        {{ $posts->links() }}
                    </div>

                @endif

                {{--                <div class="flex pt-8 lg:pt-16">--}}
                {{--                    <span--}}
                {{--                        class="cursor-pointer border-2 border-secondary px-3 py-1 font-body font-medium text-secondary">1</span>--}}
                {{--                    <span--}}
                {{--                        class="ml-3 cursor-pointer border-2 border-primary px-3 py-1 font-body font-medium text-primary transition-colors hover:border-secondary hover:text-secondary dark:border-green-light dark:text-white dark:hover:border-secondary dark:hover:text-secondary">2</span>--}}
                {{--                    <span--}}
                {{--                        class="ml-3 cursor-pointer border-2 border-primary px-3 py-1 font-body font-medium text-primary transition-colors hover:border-secondary hover:text-secondary dark:border-green-light dark:text-white dark:hover:border-secondary dark:hover:text-secondary">3</span>--}}
                {{--                    <span--}}
                {{--                        class="group ml-3 flex cursor-pointer items-center border-2 border-primary px-3 py-1 font-body font-medium text-primary transition-colors hover:border-secondary hover:text-secondary dark:border-green-light dark:text-white dark:hover:border-secondary dark:hover:text-secondary">--}}
                {{--                        Next--}}
                {{--                        <i class="bx bx-right-arrow-alt ml-1 text-primary transition-colors group-hover:text-secondary dark:text-white"></i>--}}
                {{--                    </span>--}}
                {{--                </div>--}}
            </div>
        </div>
    </div>

@endsection
