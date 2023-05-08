@extends('components.tailwindcss.templates.admin')

@section('content')

    <main class="h-full overflow-y-auto">
        <div class="container px-6 mx-auto grid">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ $title ?? '' }}</h2>
            <div class="w-full overflow-hidden px-4 py-3 mt-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <form id="form" action="{{ route($route . 'approve', $report) }}" method="post">

                    @method('put')

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Creator</label>
                        <input type="text" placeholder="Creator" disabled
                               value="{{ $report->creator->name ?? 'Anonymous' }}"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Report</label>
                        <textarea rows="4" placeholder="Report ..." disabled
                                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ $report->report ?? '' }}</textarea>
                    </div>
                    <div class="flex justify-end gap-1">
                        <a href="{{ route($route . 'index') }}"
                           class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" value="0"
                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                            <i class="fa-solid fa-xmark"></i> Reject
                        </button>
                        <button type="submit" value="1"
                                class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            <i class="fa-solid fa-check"></i> Approve
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
            $('button[type="submit"]').on('click', function () {
                const form = $(this).closest('#form')
                const isApproved = $(this).val()

                $(form).find('input[name="is_approved"]').remove()
                $(form).append(`<input type="hidden" name="is_approved" value="${isApproved}">`)
            })

            $('#form').validate({
                submitHandler: async function (form, e) {
                    e.preventDefault()

                    await callApiWithForm(form, {
                        success: function (response) {
                            if (response?.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: response?.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.href = '{{ route($route . 'index') }}'
                                })
                            }
                        },
                    })
                }
            })
        }
    </script>

@endpush
