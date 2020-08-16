@extends('yupforms::layout')

@push('head')

@endpush

@push('js')

@endpush

@section('content')

    <div class="row">
        <div class="col pb-4">
            <div class="float-right">
                <a href="{!! action('\YupForms\Http\YupFormController@edit', ['yupform' => $yupFormData->form_id]) !!}" type="button" class="btn btn-light border">
                    Back
                </a>
            </div>
        </div>
    </div>

    @if ($yupFormData)
        <div class="card" style="background-color: #f4f8fd;">
            <div class="card-body">
                <div class="row p-2">
                    <div class="offset-3 col-md-4">
                        <?php
                        $info = [
                            'submitted' => $yupFormData->created_at->format('M d, Y h:m a'),
                            //'flagged' => $yupFormData->flagged ? 'yes' : 'no',
                            'note' => $yupFormData->note,
                        ];
                        ?>
                        @foreach ($info as $label => $value)
                        <div class="row">
                            <div class="col-4 text-muted">
                                {{ $label }}
                            </div>
                            <div class="col">
                                {{ $value }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="col text-right">
                        @if ($yupFormData->flagged)
                            <span class="ml-4">
                            <i class="far fa-flag text-danger mr-4"></i>
                            <a href="{{ action('\YupForms\Http\YupFormDataController@toggleFlag', ['formdata' => $yupFormData->id]) }}" class="btn btn-sm btn-outline-secondary">unflag</a>
                        </span>
                        @else
                            <a href="{{ action('\YupForms\Http\YupFormDataController@toggleFlag', ['formdata' => $yupFormData->id]) }}" class="ml-4 btn btn-sm btn-outline-primary">flag</a>
                        @endif
                    </div>
                </div>

                <div class="row ">
                    <div class="offset-1 col-10 bg-white">
                        @foreach ($yupFormData->data as $key => $value)
                            <div class="row p-2">
                                <div class="col-3 text-muted text-right">
                                    {{ $key }}
                                </div>
                                <div class="col">
                                    {{ $value }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="offset-1 col-10">
                        <div class="accordion" id="accordionRequest">
                            <div class="" id="headingOne">
                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Request:
                                    </button>
                            </div>

                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionRequest">
                                <div class="card-body bg-white">
                                    @foreach ($yupFormData->server as $key => $value)
                                        <div class="row">
                                            <div class="col-md-3 text-muted bg-light mb-1">
                                                {{ $key }}
                                            </div>
                                            <div class="col">
                                                {{ $value }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Not Found</h5>
                <p class="card-text">There was an issue with this submission data.</p>
            </div>
        </div>
    @endif



@endsection
