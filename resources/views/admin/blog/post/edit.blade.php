@extends('components.tailwindcss.templates.admin')

@section('content')

    <main class="h-full overflow-y-auto">
        <div class="container px-6 mx-auto grid">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ $title ?? '' }}</h2>
            <div class="w-full overflow-hidden px-4 py-3 mt-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <form id="form" action="{{ route($route . 'update', $post) }}" method="post">

                    @method('put')

                    <div class="mb-4">
                        <label for="title"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Title</label>
                        <input type="text" id="title" name="title" placeholder="Title" required
                               value="{{ $post->title ?? '' }}"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <label id="title-error" class="error text-xs text-red-500" for="title"></label>
                    </div>
                    <div class="mb-4">
                        <label for="content"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Content</label>
                        <textarea id="content" rows="4" name="content" placeholder="Content ..." required
                                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ $post->content ?? '' }}</textarea>
                        <label id="content-error" class="error text-xs text-red-500" for="content"></label>
                    </div>
                    <div class="mb-4">
                        <label for="headline_image"
                               class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Headline Image</label>
                        <input type="file" id="headline_image" name="headline_image"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               placeholder="Headline Image">
                        <label id="headline_image-error" class="error text-xs text-red-500" for="headline_image"></label>
                        <img
                            src="{{ $post->headlineImage && Storage::exists($post->headlineImage->path . $post->headlineImage->hash_name) ? asset(Storage::url($post->headlineImage->path . $post->headlineImage->hash_name)) : '' }}"
                            class="max-h-[200px] block rounded-lg mx-auto {{ $post->headlineImage && Storage::exists($post->headlineImage->path . $post->headlineImage->hash_name) ? 'border mt-3' : 'hidden' }}"
                            data-type="image-preview"/>
                    </div>
                    <div class="flex items-start mb-4">
                        <div class="flex items-center h-5">
                            <input id="is_active" type="checkbox" name="is_active" @checked($post->is_active ?? false)
                                   class="w-4 h-4 bg-gray-50 rounded border border-gray-300 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800">
                        </div>
                        <label for="is_active"
                               class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-400">Active</label>
                        <label id="is_active-error" class="error text-xs text-red-500" for="is_active"></label>
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

                    await callApiWithForm(form)
                }
            })
        }
    </script>

@endpush
