@extends('components.tailwindcss.templates.pages')

@section('content')

    <div class="pb-16 lg:pb-20">
        <div class="container mx-auto">
            <div class="flex items-center pb-6">
                <img src="{{ asset('assets') }}/img/icon-story.png" alt="icon story"/>
                <h3 class="ml-3 font-body text-2xl font-semibold text-primary dark:text-white">Post</h3>
                <a href="{{ route('pages.blog') }}"
                   class="flex items-center pl-10 font-body italic text-green transition-colors hover:text-secondary dark:text-green-light dark:hover:text-secondary">
                    All posts
                    <img src="{{ asset('assets') }}/img/long-arrow-right.png" class="ml-3" alt="arrow right"/>
                </a>
            </div>
            <div class="pt-8">

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

                        @if(!empty($post['categories']))

                            <div class="flex flex-wrap gap-2 mb-3">

                                @forelse($post['categories'] as $category)

                                    <span
                                        class="inline-block rounded-full px-2 py-1 font-body text-sm {{ $mapBackgroundAndTextColor[rand(0, count($mapBackgroundAndTextColor) - 1)] }}">{{ $category }}</span>

                                @empty @endforelse

                            </div>

                        @endif

                        <a href="{{ route('pages.post') }}"
                           class="block font-body text-lg font-semibold text-primary transition-colors hover:text-green dark:text-white dark:hover:text-secondary">
                            {{ $post['title'] ?? '' }}
                        </a>
                        <p class="font-body font-light text-primary dark:text-white">{{ $post['subtitle'] ?? '' }}</p>
                        <div class="flex items-center pt-4">
                            <p class="pr-2 font-body font-light text-primary dark:text-white">
                                {{ ($post['created_at'] ?? null) ? $post['created_at']->format('F d, Y') : '' }}
                            </p>
                        </div>
                    </div>

                @empty @endforelse

            </div>
        </div>
    </div>

@endsection
