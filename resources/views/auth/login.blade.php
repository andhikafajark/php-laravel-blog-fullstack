<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>{{ $title ?? '' }} | {{ env('APP_NAME') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>

    @vite('resources/css/app.css')

</head>
<body>
<div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
    <div class="flex-1 h-full max-w-4xl mx-auto overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800">
        <div class="flex flex-col overflow-y-auto md:flex-row">
            <div class="h-32 md:h-auto md:w-1/2">
                <img aria-hidden="true" class="object-cover w-full h-full dark:hidden"
                     src="{{ asset('assets') }}/img/login-office.jpeg" alt="Office"/>
                <img aria-hidden="true" class="hidden object-cover w-full h-full dark:block"
                     src="{{ asset('assets') }}/img/login-office-dark.jpeg" alt="Office"/>
            </div>
            <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
                <div class="w-full">
                    <form id="form" action="{{ route($route . 'process') }}" method="post">
                        <h1 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">Login</h1>
                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Email</span>
                            <input
                                type="email" id="email" name="email" placeholder="test@test.com" required
                                class="block w-full mt-1 rounded-md border-1 p-1.5 text-gray-900 shadow-sm dark:border-gray-600 placeholder:text-gray-400 focus:border-purple-400 dark:bg-gray-700 dark:text-gray-300"/>
                            <label id="email-error" class="error text-xs text-red-700" for="email"></label>
                        </label>
                        <label class="block mt-4 text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Password</span>
                            <input type="password" id="password" name="password" placeholder="***************" required
                                   class="block w-full mt-1 rounded-md border-1 p-1.5 text-gray-900 shadow-sm dark:border-gray-600 placeholder:text-gray-400 focus:border-purple-400 dark:bg-gray-700 dark:text-gray-300"/>
                            <label id="password-error" class="error text-xs text-red-700" for="password"></label>
                        </label>
                        <button type="submit"
                                class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            Log in
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.19/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function () {
        handler()
    })

    function handler() {
        $('#form').validate({
            submitHandler: function (form, e) {
                e.preventDefault()

                callApiWithForm(form, {
                    success: function (response) {
                        if (response?.success) {
                            Swal.fire({
                                icon: 'success',
                                title: response?.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = response?.data?.route
                            })
                        }
                    },
                })
            }
        })
    }

    async function callApiWithForm(form, options = {}) {
        const url = $(form).attr('action')
        const method = $(form).attr('method')
        const formData = new FormData(form)

        return apiCall({
            url,
            type: method,
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            beforeSend: function () {
                $(form).find('.error').hide()
                $(form).find('button[type="submit"]').attr('disabled', true)
            },
            success: function (response) {
                if (response?.success) {
                    Swal.fire({
                        icon: 'success',
                        title: response?.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            },
            error: function (response) {
                const responseJSON = response?.responseJSON

                if (response?.status === 422) {
                    for (const [key, value] of Object.entries(responseJSON?.data)) {
                        const type = $(form).find(`[name="${key}"]`).attr('type')

                        if (type === 'file') {
                            $(form).find(`[name="${key}"]`).parent().siblings('.error').html(value).css('display', 'block')
                        } else {
                            $(form).find(`[name="${key}"]`).siblings('.error').html(value).css('display', 'block')
                        }
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: responseJSON?.message,
                        showConfirmButton: false,
                        timer: 1000
                    })
                }
            },
            complete: function () {
                $(form).find('button[type="submit"]').attr('disabled', false)
            },
            ...options
        })
    }

    async function apiCall(options = {}) {
        return $.ajax({
            ...options,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                ...options?.headers
            },
        })
    }
</script>
</html>
