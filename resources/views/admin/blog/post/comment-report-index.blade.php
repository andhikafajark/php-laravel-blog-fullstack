@extends('components.tailwindcss.templates.admin')

@push('styles')

    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">

@endpush

@section('content')

    <main class="h-full overflow-y-auto">
        <div class="container px-6 mx-auto grid">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">{{ $title ?? '' }}</h2>
            <div class="w-full overflow-hidden px-4 py-3 mt-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <table id="table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Report</th>
                        <th>Creator</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </main>

@endsection

@push('scripts')

    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function () {
            init()
            handler()
        });

        function init() {
            window.filterDataTable = {}

            datatables()
        }

        function datatables() {
            if (window.dataTable !== undefined) window.dataTable.destroy()

            window.dataTable = $('#table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route( $route. 'index' ) }}',
                    data: {filter: window.filterDataTable}
                },
                columns: [
                    {data: 'DT_RowIndex', orderable: false, searchable: false, className: 'dt-center'},
                    {data: 'report'},
                    {data: 'creator', name: 'creator.name'},
                    {data: 'status', orderable: false, searchable: false},
                    {data: 'action', orderable: false, searchable: false}
                ]
            });
        }

        function handler() {
            $('#table').on('click', '.delete', async function () {
                const resAlert = await showAlertConfirm({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    confirmButtonColor: '#d33'
                })

                if (!resAlert) return;

                const url = $(this).data('url')

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

                            datatables()
                        }
                    }
                })
            })
        }
    </script>

@endpush
