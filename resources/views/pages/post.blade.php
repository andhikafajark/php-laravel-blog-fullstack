@extends('components.tailwindcss.templates.pages')

@section('content')

    <div>
        <div class="container mx-auto mb-10">
            <div class="pt-16 lg:pt-20">
                <div class="border-b border-grey-lighter pb-8 sm:pb-12">

                    @php
                        $mapBackgroundAndTextColor = [
                            'bg-green-light text-green',
                            'bg-grey-light text-blue-dark',
                            'bg-yellow-light text-yellow-dark',
                            'bg-blue-light text-blue'
                        ];
                    @endphp
                    @if($post->categories->isNotEmpty())

                        <div class="flex flex-wrap gap-2 mb-3">

                            @forelse($post->categories as $category)

                                <span
                                    class="inline-block rounded-full px-2 py-1 font-body text-sm {{ $mapBackgroundAndTextColor[rand(0, count($mapBackgroundAndTextColor) - 1)] }}">{{ $category->title ?? '' }}</span>

                            @empty @endforelse

                        </div>

                    @endif

                    <h2 class="block font-body text-3xl font-semibold leading-tight text-primary dark:text-white sm:text-4xl md:text-5xl">
                        {{ $post->title ?? '' }}
                    </h2>
                    <div class="flex items-center pt-5 sm:pt-8">
                        <p class="pr-2 font-body font-light text-primary dark:text-white">{{ ($post->created_at ?? null) ? $post->created_at->format('d F Y') : '' }}</p>
                    </div>
                </div>
                <div class="prose max-w-none border-b border-grey-lighter py-8 dark:prose-dark sm:py-12">
                    {!!  $post->content ?? '' !!}
                </div>
                <div class="flex items-center py-10">
                    <span class="pr-5 font-body font-medium text-primary dark:text-white">Share</span>
                    <a href="/">
                        <i class="bx bxl-facebook text-2xl text-primary transition-colors hover:text-secondary dark:text-white dark:hover:text-secondary"></i>
                    </a>
                    <a href="/">
                        <i class="bx bxl-twitter pl-2 text-2xl text-primary transition-colors hover:text-secondary dark:text-white dark:hover:text-secondary"></i>
                    </a>
                    <a href="/">
                        <i class="bx bxl-linkedin pl-2 text-2xl text-primary transition-colors hover:text-secondary dark:text-white dark:hover:text-secondary"></i>
                    </a>
                    <a href="/">
                        <i class="bx bxl-reddit pl-2 text-2xl text-primary transition-colors hover:text-secondary dark:text-white dark:hover:text-secondary"></i>
                    </a>
                </div>
                <nav role="navigation" aria-label="Pagination Navigation"
                     class="flex justify-between flex-col md:flex-row gap-3 mb-10">

                    @if($previous)

                        <div
                            class="bg-white border border-gray-300 rounded-md focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 px-4 py-2 transition ease-in-out duration-150">
                            <a href="{{ route('pages.post', ($previous->slug ?? null)) }}" rel="previous"
                               class="text-ellipsis line-clamp-2 text-sm font-medium text-gray-700 leading-5 hover:text-gray-500 active:bg-gray-100 active:text-gray-700">
                                {{ $previous->title ?? '' }}
                            </a>
                        </div>

                    @else

                        <div></div>

                    @endif
                    @if($next)

                        <div
                            class="bg-white border border-gray-300 rounded-md focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 px-4 py-2 transition ease-in-out duration-150">
                            <a href="{{ route('pages.post', ($next->slug ?? null)) }}" rel="next"
                               class="text-ellipsis line-clamp-2 text-sm font-medium text-gray-700 leading-5 hover:text-gray-500 active:bg-gray-100 active:text-gray-700">
                                {{ $next->title ?? '' }}
                            </a>
                        </div>

                    @endif

                </nav>
            </div>
            <section class="bg-white dark:bg-gray-900 py-8 lg:py-16 border-t border-gray-200 dark:border-gray-700">
                <div class="max-w-2xl mx-auto px-4">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">
                            Comments (<span id="total-comments">0</span>)
                        </h2>
                    </div>
                    <form id="form" action="{{ route('pages.post.comment.store', ($post->slug ?? null)) }}"
                          method="post" class="mb-6">
                        <div
                            class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                            <label for="comment" class="sr-only">Your comment</label>
                            <textarea id="comment" name="comment" rows="6" placeholder="Write a comment..." required
                                      class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"></textarea>
                            <label id="comment-error" class="error text-xs text-red-500" for="comment"></label>
                        </div>
                        <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Post comment
                        </button>
                    </form>
                    <div id="comments-container"></div>
                </div>
            </section>
        </div>
    </div>

    <div id="report-modal" tabindex="-1" aria-hidden="true"
         class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 lg:text-2xl dark:text-white">
                        Report Comment
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <form id="form-report-comment" action="javascript:void(0)" method="post">
                        <div
                            class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                            <label for="report" class="sr-only">Your report</label>
                            <textarea id="report" name="report" rows="6" placeholder="Write a report..." required
                                      class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"></textarea>
                            <label id="report-error" class="error text-xs text-red-500" for="report"></label>
                        </div>
                        <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Send report
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://unpkg.com/flowbite@1.4.4/dist/flowbite.js"></script>
    <script>
        let dropdownComments = []
        let modalReport = null

        $(document).ready(function () {
            init()
            handler()
        })

        function init() {
            initComment()

            modalReport = new Modal(document.getElementById('report-modal'))
        }

        function handler() {
            // Create Comment
            $('#form').validate({
                submitHandler: function (form, e) {
                    e.preventDefault()

                    callApiWithForm(form, {
                        success: function (response) {
                            if (response?.success) {
                                showAlert({
                                    icon: 'success',
                                    title: response?.message,
                                    timer: 1500
                                })

                                $(form).trigger('reset')
                                initComment()
                            }
                        },
                    })
                }
            })

            // Reply Comment
            $(document).on('click', 'button[data-type="reply-button"]', function () {
                const parentElement = $(this).closest('[data-type="reply-button-container"]')

                $('[data-type="reply-icon"]').attr('fill', 'none').attr('stroke', 'currentColor')

                if (parentElement.siblings('form[data-type="form-comment-reply"]').length > 0) {
                    $('body form[data-type="form-comment-reply"]').remove()

                    return;
                }

                $(this).find('[data-type="reply-icon"]').attr('fill', '#1A56DB').attr('stroke', 'white')
                $('body form[data-type="form-comment-reply"]').remove()

                const parentId = $(this).data('parent-id')

                const formComment = `
                    <form id="form" action="{{ route('pages.post.comment.store', ($post->slug ?? null)) }}" method="post" data-type="form-comment-reply">
                        <input type="hidden" name="parent_id" value="${parentId}">
                        <div
                            class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                            <label for="comment" class="sr-only">Your comment</label>
                            <textarea id="comment" name="comment" rows="6" placeholder="Write a comment..." required
                                      class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"></textarea>
                            <label id="comment-error" class="error text-xs text-red-500" for="comment"></label>
                        </div>
                        <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Reply comment
                        </button>
                    </form>`

                parentElement.after(formComment)
            })

            $(document).on('submit', 'form[data-type="form-comment-reply"]', function (e) {
                e.preventDefault()

                const form = this

                callApiWithForm(form, {
                    success: function (response) {
                        if (response?.success) {
                            showAlert({
                                icon: 'success',
                                title: response?.message,
                                timer: 1500
                            })

                            $(form).trigger('reset')
                            initComment()
                        }
                    },
                })
            })

            // Edit Comment
            $(document).on('click', 'button[data-type="edit-comment-button"]', function () {
                const commentUuid = $(this).data('uuid')
                const parentCommentHeaderContainerElement = $(this).closest('[data-type="comment-header-container"]')
                const commentContainerElement = parentCommentHeaderContainerElement.siblings('[data-type="comment-container"]')

                dropdownComments[parentCommentHeaderContainerElement.data('index')].dropdown.hide()

                $('body form[data-type="form-comment-edit"]').remove()
                $('body div[data-type="comment-container"]').removeClass('hidden')

                commentContainerElement.addClass('hidden')

                const formComment = `
                    <form id="form" action="${('{{ route('pages.post.comment.update', [($post->slug ?? null), 'COMMENT_UUID']) }}').replace('COMMENT_UUID', commentUuid)}" method="post" data-type="form-comment-edit">
                        <input type="hidden" name="_method" value="put">
                        <div
                            class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                            <label for="comment" class="sr-only">Your comment</label>
                            <textarea id="comment" name="comment" rows="6" placeholder="Write a comment..." required
                                      class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800">${commentContainerElement.html()}</textarea>
                            <label id="comment-error" class="error text-xs text-red-500" for="comment"></label>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Edit comment
                            </button>
                            <button type="button" data-type="cancel-edit-comment"
                                    class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                                Cancel
                            </button>
                        </div>
                    </form>`

                parentCommentHeaderContainerElement.siblings('[data-type="comment-container"]').after(formComment)
            })

            $(document).on('click', 'button[data-type="cancel-edit-comment"]', function () {
                const parentFormCommentEditElement = $(this).closest('[data-type="form-comment-edit"]')

                parentFormCommentEditElement.siblings('[data-type="comment-container"]').removeClass('hidden')
                parentFormCommentEditElement.remove()
            })

            $(document).on('submit', 'form[data-type="form-comment-edit"]', function (e) {
                e.preventDefault()

                const form = this

                callApiWithForm(form, {
                    success: function (response) {
                        if (response?.success) {
                            showAlert({
                                icon: 'success',
                                title: response?.message,
                                timer: 1500
                            })

                            $(form).trigger('reset')
                            initComment()
                        }
                    },
                })
            })

            // Remove Comment
            $(document).on('click', 'button[data-type="remove-comment-button"]', async function () {
                const commentUuid = $(this).data('uuid')

                const resAlert = await showAlertConfirm({
                    confirmButtonColor: '#d33'
                })

                if (!resAlert) return;

                const url = ('{{ route('pages.post.comment.destroy', [($post->slug ?? null), 'COMMENT_UUID']) }}').replace('COMMENT_UUID', commentUuid)

                await apiCall({
                    url,
                    type: 'delete',
                    success: function (response) {
                        if (response?.success) {
                            showAlert({
                                icon: 'success',
                                title: response?.message,
                                timer: 1500
                            })

                            initComment()
                        }
                    }
                })
            })

            // Report Comment
            $(document).on('click', 'button[data-type="report-comment-button"]', function () {
                const commentUuid = $(this).data('uuid')

                $('#form-report-comment')
                    .attr('action', ('{{ route('pages.post.comment.report', [($post->slug ?? null), 'COMMENT_UUID']) }}').replace('COMMENT_UUID', commentUuid))
                    .trigger('reset')

                modalReport.show()
            })

            $('#form-report-comment').validate({
                submitHandler: function (form, e) {
                    e.preventDefault()

                    callApiWithForm(form, {
                        success: function (response) {
                            if (response?.success) {
                                showAlert({
                                    icon: 'success',
                                    title: response?.message,
                                    timer: 1500
                                })

                                $(form).trigger('reset')
                                modalReport.hide()
                                initComment()
                            }
                        },
                    })
                }
            })
        }

        async function initComment() {
            const resData = await apiCall({
                url: '{{ route('pages.post.comment.get-all', ($post->slug ?? null)) }}',
                success: function () {
                },
                error: function () {
                },
            })

            if (!resData?.success) return;

            let totalComments = 0
            const optionsDateFormat = {
                month: 'long',
                year: 'numeric',
                day: 'numeric'
            }

            dropdownComments = []
            $('#comments-container').empty()
            let increment = 0

            const generateComment = (data, index = 0) => {
                return data.forEach((datum) => {
                    const comment = `
                        <article class="p-6 text-base bg-white border-gray-200 dark:border-gray-700 dark:bg-gray-900 ${index === 0 ? 'border-t' : ''}" style="margin-left: ${index * 1.5}rem;">
                            <header class="flex justify-between items-center mb-2" data-type="comment-header-container" data-index="${increment}">
                                <div class="flex items-center">
                                    <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white">
                                        <img
                                            class="mr-2 w-6 h-6 rounded-full"
                                            src="https://flowbite.com/docs/images/people/profile-picture-2.jpg"
                                            alt="Michael Gough">${datum?.creator?.name || 'Anonymous'}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        <time datetime="${datum?.created_at || null}">${datum?.created_at ? (new Date(datum.created_at)).toLocaleDateString('id-ID', optionsDateFormat) : ''}</time>
                                    </p>
                                </div>
                                <button id="dropdownComment${index}${datum?.uuid || null}Button" data-dropdown-toggle="dropdownComment${index}${datum?.uuid || null}"
                                        class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-400 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                                        type="button">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                    </svg>
                                    <span class="sr-only">Comment settings</span>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="dropdownComment${index}${datum?.uuid || null}" class="hidden z-10 w-36 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownMenuIconHorizontalButton">
                                        <li>
                                            <button type="button" data-type="edit-comment-button" data-uuid="${datum?.uuid || null}" class="block w-full text-left py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</button>
                                        </li>
                                        <li>
                                            <button type="button" data-type="remove-comment-button" data-uuid="${datum?.uuid || null}" class="block w-full text-left py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Remove</button>
                                        </li>
                                        <li>
                                            <button type="button" data-type="report-comment-button" data-uuid="${datum?.uuid || null}" data-modal-target="report-modal" data-modal-toggle="report-modal" class="block w-full text-left py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Report</button>
                                        </li>
                                    </ul>
                                </div>
                            </header>
                            <div class="text-gray-500 dark:text-gray-400" data-type="comment-container">${datum?.comment || ''}</div>
                            <div class="flex items-center my-4 space-x-4" data-type="reply-button-container">
                                <button type="button" data-type="reply-button" data-parent-id="${datum?.id || null}"
                                        class="flex items-center text-sm text-gray-500 hover:underline dark:text-gray-400">
                                    <svg aria-hidden="true" class="mr-1 w-4 h-4" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" data-type="reply-icon">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    Reply
                                </button>
                            </div>
                        </article>`

                    $('#comments-container').append(comment)

                    dropdownComments[increment++] = {
                        dropdown: new Dropdown(
                            document.getElementById(`dropdownComment${index}${datum?.uuid || null}`),
                            document.getElementById(`dropdownComment${index}${datum?.uuid || null}Button`),
                            {
                                placement: 'bottom'
                            }
                        )
                    }

                    totalComments++

                    if (datum?.children?.length > 0) {
                        generateComment(datum.children, (index + 1))
                    }
                })
            }

            generateComment(resData.data)

            $('#total-comments').html(totalComments)}
    </script>

@endpush
