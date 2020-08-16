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
                "url": '{!! action('\YupForms\Http\YupFormDataController@formDataList', ['id' => $yupForm->id] + Request::all()) !!}',
            }
            , order: [[ 0, "desc" ]]
            , pageLength: 50
            , columnDefs: [
                {
                    targets: 0,
                    data: 'created_at',
                    orderable: true,
                    render: function (data, type, row, meta) {
                        created = luxon.DateTime.fromISO(data, {zone: 'utc'}).setZone('America/Los_Angeles');

                        return created.toFormat("MM/dd/yyyy")
                            + '<span class="text-muted"> ' + created.toFormat("h:m a") + '</span>';
                    }
                },
                {
                    targets: 1,
                    data: 'data',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        var show = null;
                        var stringified = JSON.stringify(data, show, 2);

                        dataHtml = '<pre class="">' + stringified.replace(/{|}|"|,/g, '') + '</pre>' ;
                        dataHtml += row.note ? '<pre class="mb-0 ml-3 text-muted">notes: ' + row.note + '</pre>' : '' ;

                        /*show = [
                            //'REMOTE_ADDR',
                            'HTTP_HOST',
                            'HTTP_REFERER',
                        ];
                        stringified = JSON.stringify(row.server, show, 2);
                        dataHtml += '<pre class="text-muted small">' + stringified.replace(/{|}|"|,/g, '') + '</pre>' ;
                        */

                        return dataHtml;
                    }
                },
                {
                    targets: 2,
                    data: 'flagged',
                    className: "text-center",
                    render: function (data, type, row, meta) {
                        html = '';
                        if (data == 1) {
                            html = '<i class="far fa-flag text-danger"></i>';
                        }

                        return html;
                    }
                },
                {
                    targets: 3,
                    orderable: false,
                    data: "",
                    render: function (data, type, row, meta) {
                        url = '{{ action('\YupForms\Http\YupFormDataController@show', ['formdata' => 'jsId']) }}';

                        html = '<nobr>';
                        html +=
                            '<a class="text-secondary m-2" href="' + url.replace(/jsId/g, row.id) + '" role="button">'
                            + '<i class="fas fa-eye"></i>'
                            + '</a>';

                        html += '</nobr>';

                        return html;
                    }
                },
                /*{
                    targets: 2,
                    data: 'server',
                    orderable: false,
                    render: function (data, type, row, meta) {
                        //console.log(data);
                        var show = [
                            //'REMOTE_ADDR',
                            'HTTP_HOST',
                            'HTTP_REFERER',
                        ];
                        var stringified = JSON.stringify(data, show, 2);

                        return '<pre class="text-muted small">' + stringified.replace(/{|}|"|,/g, '') + '</pre>' ;
                    }
                },*/
            ]
        });
    </script>
@endpush

@section('content')
    <div class="text-secondary">
        {{ $yupForm->description }}
    </div>
    <div class="row p-2">
        <div class="offset-4 col-md-4 p-2 border bg-light rounded">
            <div class="row">
                <div class="col text-muted">
                    Form Enabled:
                </div>
                <div class="col">
                    {!!
                        $yupForm->status
                        ? '<span class="text-success">Yes</span>'
                        : '<span class="text-danger">Not Enabled</span>'
                    !!}
                </div>
            </div>
            <div class="row">
                <div class="col text-muted">
                    Host Restriction:
                </div>
                <div class="col">
                    {!! $yupForm->host ??'<span class="text-muted">none</span>' !!}
                </div>
            </div>
        </div>

        <div class="col">
            <div class="text-right">
                <a href="{!! action('\YupForms\Http\YupFormController@index') !!}" type="button" class="btn btn-light border">
                    Back
                </a>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" id="subscriber-tab" data-toggle="tab" href="#subscriber" role="tab" aria-controls="contact" aria-selected="false">
                Submissions
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="embed-tab" data-toggle="tab" href="#embed" role="tab" aria-controls="contact" aria-selected="false">Embed</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="contact" aria-selected="false">Settings</a>
        </li>
    </ul>

    <div class="tab-content p-4" id="myTabContent">
        <div class="tab-pane fade show active" id="subscriber" role="tabpanel" aria-labelledby="subscriber-tab">
            <h3 class="mb-4">
                Submissions
                <div class="float-right">
                    <a href="{{ action('\YupForms\Http\YupFormController@downloadCsv', ['yupform' => $yupForm->id]) }}"
                       class="btn btn-light border"
                       title="Download csv"
                    >
                        CSV
                    </a>
                </div>
            </h3>

            <table class="table" id="indexTable">
                <thead>
                <th>date</th>
                <th>data</th>
                <th>flagged</th>
                <th></th>
                </thead>
            </table>
        </div>

        <div class="tab-pane fade" id="embed" role="tabpanel" aria-labelledby="embed-tab">
            @include('yupforms::admin.form.partial.edit-embed')
        </div>

        <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
            @include('yupforms::admin.form.partial.edit-settings')
        </div>
    </div>

@endsection
