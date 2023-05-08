@if(is_null($data->is_approved ?? null))

    <div class="flex justify-center items-center gap-1">
        <a href="{{ route($route . 'show', $data) }}"
           class="text-blue-400 hover:text-white border border-blue-400 hover:bg-blue-500 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1.5 text-center dark:border-blue-300 dark:text-blue-300 dark:hover:text-white dark:hover:bg-blue-400 dark:focus:ring-blue-900">
            <i class="fa-solid fa-circle-info"></i> Show
        </a>
    </div>

@endif
