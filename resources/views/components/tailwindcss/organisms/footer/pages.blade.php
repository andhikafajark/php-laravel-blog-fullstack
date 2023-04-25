<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.19/dist/sweetalert2.all.min.js"></script>
<script src="{{ asset('assets') }}/js/main.js"></script>
<script>
    $(document).ready(function () {
        globalHandler()
    })

    function globalHandler() {
        $('body').on('change', 'input[type="file"]', function (e) {
            const imagePreviewElement = $(e.target).siblings('[data-type="image-preview"]')

            if (!e.target?.files?.length) {
                return imagePreviewElement
                    .removeClass('border mt-3 hidden')
                    .attr('src', '')
            }

            const mimeType = e.target.files[0].type

            if (mimeType.split('/')[0] !== 'image') {
                return imagePreviewElement
                    .removeClass('border mt-3 hidden')
                    .attr('src', '')
            }

            return imagePreviewElement
                .addClass('border mt-3')
                .attr('src', URL.createObjectURL(e.target.files[0]))
                .removeClass('hidden')
        })

        $(document).on('click', '#logout', async function (e) {
            e.preventDefault()

            const resAlert = await showAlertConfirm({
                confirmButtonColor: '#d33'
            })

            if (!resAlert) return;

            const url = $(this).attr('href')

            await apiCall({
                url,
                success: function (response) {
                    if (response?.success) {
                        showAlert({
                            icon: 'success',
                            title: response?.message,
                            timer: 1500
                        }).then(() => {
                            window.location.href = response?.data?.route
                        })
                    }
                }
            })
        })
    }

    async function showAlert(option = {}) {
        return await Swal.fire({
            icon: 'success',
            showConfirmButton: false,
            ...option
        })
    }

    async function showAlertConfirm(option = {}) {
        return await showAlert({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Yes',
            ...option
        }).then((result) => result.isConfirmed)
    }

    async function apiCall(options = {}) {
        return $.ajax({
            success: function (response) {
                if (response?.success) {
                    showAlert({
                        icon: 'success',
                        title: response?.message,
                        timer: 1500
                    })
                }
            },
            error: function (response) {
                showAlert({
                    icon: 'error',
                    title: response?.responseJSON?.message,
                    timer: 1500
                })
            },
            ...options,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                ...options?.headers
            },
        })
    }

    async function callApiWithForm(form, options = {}) {
        const resAlert = await showAlertConfirm()

        if (!resAlert) return;

        const url = $(form).attr('action')
        const method = $(form).attr('method')
        const formData = new FormData(form)

        $(form).find('input[type="checkbox"]').each(function (_, element) {
            formData.set($(element).attr('name'), $(element).is(':checked') ? 1 : 0)
        })

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
                    showAlert({
                        icon: 'success',
                        title: response?.message,
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
                    showAlert({
                        icon: 'error',
                        title: responseJSON?.message,
                        timer: 1500
                    })
                }
            },
            complete: function () {
                $(form).find('button[type="submit"]').attr('disabled', false)
            },
            ...options
        })
    }

    async function callApiWithVue(form, options) {
        const resAlert = await showAlertConfirm()

        if (!resAlert) return;

        const methodInput = $(form).find('input[name="_method"]')
        const method = methodInput.length ? methodInput.val() : form.method
        const url = form.action

        return apiCall({
            url,
            type: method,
            beforeSend: function () {
                $(form).find('button[type="submit"]').attr('disabled', true)
            },
            success: function (response) {
                if (response?.success) {
                    showAlert({
                        icon: 'success',
                        title: response?.message,
                        timer: 1500
                    })
                }
            },
            error: function (response) {
                const responseJSON = response?.responseJSON

                if (response?.status === 422) {
                    vue.$refs.form.setErrors(responseJSON?.data)
                } else {
                    showAlert({
                        icon: 'error',
                        title: responseJSON?.message,
                        timer: 1500
                    })
                }
            },
            complete: function () {
                $(form).find('button[type="submit"]').attr('disabled', false)
            },
            ...options
        })
    }
</script>

@stack('scripts')

</html>
