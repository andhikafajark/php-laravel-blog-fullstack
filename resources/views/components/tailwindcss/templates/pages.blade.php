@include('components.tailwindcss.organisms.header.pages')

<body x-data="global()" x-init="themeInit()" :class="isMobileMenuOpen ? 'max-h-screen overflow-hidden relative' : ''"
      class="dark:bg-primary">
    <div id="main">

        @include('components.tailwindcss.organisms.topbar.pages')

        @yield('content')

        <div class="container mx-auto">
            <div class="flex flex-col items-center justify-between border-t border-grey-lighter py-10 sm:flex-row sm:py-12">
                <div class="mr-auto flex flex-col items-center sm:flex-row">
                    <a href="{{ route('pages.index') }}" class="mr-auto sm:mr-6">
                        <img src="{{ asset('assets') }}/img/logo.svg" alt="logo"/>
                    </a>
                    <p class="pt-5 font-body font-light text-primary dark:text-white sm:pt-0">©{{ date('Y') }} {{ env('APP_NAME') }}.</p>
                </div>
                <div class="mr-auto flex items-center pt-5 sm:mr-0 sm:pt-0">
                    <a href="https://github.com/ " target="_blank">
                        <i class="text-4xl text-primary dark:text-white pl-5 hover:text-secondary dark:hover:text-secondary transition-colors bx bxl-github"></i>
                    </a>
                    <a href="https://codepen.io/ " target="_blank">
                        <i class="text-4xl text-primary dark:text-white pl-5 hover:text-secondary dark:hover:text-secondary transition-colors bx bxl-codepen"></i>
                    </a>
                    <a href="https://www.linkedin.com/ " target="_blank">
                        <i class="text-4xl text-primary dark:text-white pl-5 hover:text-secondary dark:hover:text-secondary transition-colors bx bxl-linkedin"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

@include('components.tailwindcss.organisms.footer.pages')
