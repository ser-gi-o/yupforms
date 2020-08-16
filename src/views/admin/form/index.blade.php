@extends('yupforms::layout')

@push('head')
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush

@push('js')
    <script src="//cdn.jsdelivr.net/npm/luxon@1.21.3/build/global/luxon.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
        $('#indexTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": '{!! action('\YupForms\Http\YupFormController@formIndexList', Request::all()) !!}',
            }
            , "columnDefs": [
                {
                    targets: 0,
                    data: 'name',
                    className: "",
                },
                {
                    targets: 1,
                    data: 'description',
                    className: ""
                },
                {
                    targets: 2,
                    data: 'submissions',
                    className: "text-center"
                },
                {
                    targets: 3,
                    data: 'created_at',
                    className: "text-center",
                    render: function (data, type, row, meta) {
                        //todo: set timezone
                        created = luxon.DateTime.fromISO(data, {zone: 'utc'}).setZone('America/Los_Angeles');
                        console.log(created);
                        return created.toFormat("MM/dd/yyyy")
                            + '<small class="text-muted"> ' + created.toFormat("h:m a") + '</small>';
                    }
                },
                {
                    targets: 4,
                    data: 'status',
                    className: "text-center",
                    render: function (data, type, row, meta) {
                        html = '<i class="fas fa-circle ' + (data ? 'text-success' : 'text-warning') +'"></i>';
                        return html;
                    }
                },
                {
                    "targets": 5,
                    "orderable": false,
                    "data": "",
                    "render": function (data, type, row, meta) {
                        url = '{{ action('\YupForms\Http\YupFormController@edit', ['yupform' => 'jsId']) }}';
                        html = '<a class="text-secondary m-2" href="' + url.replace(/jsId/g, row.id) + '" role="button"><i class="fas fa-eye"></i></a>';
                        return html;
                    }
                },
            ]
            , "order": [[ 0, "asc" ]]
            , "pageLength": 25
        });
    </script>
@endpush

@section('content')
    @include('yupforms::admin.form.partial.modal-form-create')

    <div class="row">
        <div class="col pb-4">
            <div class="float-right">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createYupFormModal">
                    Create Form
                </button>
            </div>
        </div>
    </div>

    <div class="">
        <table class="table" id="indexTable">
            <thead>
            <th>name</th>
            <th>description</th>
            <th>submissions</th>
            <th>created</th>
            <th>status</th>
            <th></th>
            </thead>
        </table>
    </div>

    <!--yf-data-table></yf-data-table-->
@endsection
