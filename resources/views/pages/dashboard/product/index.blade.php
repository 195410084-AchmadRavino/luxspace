<x-app-layout>
    <x-slot name="header">
        <head> 
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Product') }}
            </h2>
        </head>
    </x-slot>

    <x-slot name="script">
        <script>
            // AJAX Datatable
            $(document).ready(function() {
                var datatable = $('#CrudTable').dataTable({
                    ajax: {
                        url: '{!! url()->current() !!}'
                    },
                    columns: [
                        {data: 'id', name: 'id', width: '5%'},
                        {data: 'name', name: 'name'},
                        {data: 'price', name: 'price'},
                        {data: 'berat', name: 'berat'},
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: true,
                            width: '25%'
                        }
                    ]
                });
            });
        </script>
    </x-slot>
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-10">
                <a href="{{ route('dashboard.product.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-lg">
                    + Create Product
                </a>
            </div>
            <div class="shadow overflow-hidden sm-rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <table id="CrudTable">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Berat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
