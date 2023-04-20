@include('components.tailwindcss.organisms.header.admin')

<body>
<div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">

    @include('components.tailwindcss.organisms.sidebar.admin')

    <div class="flex flex-col flex-1 w-full">

        @include('components.tailwindcss.organisms.topbar.admin')

        @yield('content')

    </div>
</div>
</body>

@include('components.tailwindcss.organisms.footer.admin')
