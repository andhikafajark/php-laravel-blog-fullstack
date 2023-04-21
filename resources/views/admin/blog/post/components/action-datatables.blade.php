<div class="flex justify-center items-center gap-1">
    <a href="{{ route($route . 'edit', $data) }}"
       class="text-yellow-400 hover:text-white border border-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-3 py-1.5 text-center dark:border-yellow-300 dark:text-yellow-300 dark:hover:text-white dark:hover:bg-yellow-400 dark:focus:ring-yellow-900">
        <i class="fa-solid fa-pen-to-square"></i> Edit
    </a>
    <button type="button" data-url="{{ route($route . 'destroy', $data) }}"
            class="delete text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-1.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
        <i class="fa-solid fa-trash"></i> Delete
    </button>
</div>
