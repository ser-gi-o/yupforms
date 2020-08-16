@extends('yupforms::layout')

@push('head')
<style>
    label {
        display: block;
        width: 100%;
        margin: 10px;
    }

    input, label {
        display: block;
        width: 100%;
        margin: 10px 0;
    }
</style>
@endpush

@push('js')

@endpush

@section('content')
    @if (is_null($yupForm))
        <h2 class="text-center text-danger">
            Error. Form not found. {{ $publicId }}
        </h2>
    @else
        @if (request('type') == 'ajax')
            @include('yupforms::admin.form.template.ajax', ['publicId' => $yupForm->public_id])
        @else
            @include('yupforms::admin.form.template.html', ['publicId' => $yupForm->public_id])
        @endif
    @endif

@endsection
