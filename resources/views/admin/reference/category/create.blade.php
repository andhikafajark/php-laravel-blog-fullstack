@extends('components.tailwindcss.templates.admin')

@section('content')

    <main class="h-full overflow-y-auto">
        <div class="container px-6 mx-auto grid">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ $title ?? '' }}</h2>
            <div class="w-full overflow-hidden px-4 py-3 mt-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <form id="form" action="{{ route($route . 'store') }}" method="post">
                    <div class="mb-4">
                        <label for="title"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Title</label>
                        <input type="text" id="title" name="title" placeholder="Title" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <label id="title-error" class="error text-xs text-red-500" for="title"></label>
                    </div>
                    <div class="mb-4">
                        <label for="type"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Type</label>
                        <select id="type" name="type" required data-placeholder="Choose"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 select2">

                            @forelse($types as $type)

                                <option value="{{ $type }}">{{ str($type)->title() }}</option>

                            @empty @endforelse

                        </select>
                        <label id="type-error" class="error text-xs text-red-500" for="type"></label>
                    </div>
                    <div class="flex justify-end gap-1">
                        <a href="{{ route($route . 'index') }}"
                           class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <i class="fas fa-save"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

@endsection

@push('scripts')

    <script>
        $(document).ready(function () {
            handler()
        })

        function handler() {
            $('#form').validate({
                submitHandler: async function (form, e) {
                    e.preventDefault()

                    await callApiWithForm(form, {
                        success: function (response) {
                            if (response?.success) {
                                showAlert({
                                    icon: 'success',
                                    title: response?.message,
                                    timer: 1500
                                })

                                $(form).find('.select2').val(null).trigger('change')
                                initSelect2()
                                $(form).trigger('reset')
                            }
                        }
                    })
                }
            })
        }
    </script>

@endpush
