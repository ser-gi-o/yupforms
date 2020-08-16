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

    <h3>test next</h3>
    <form action="https://dp.mailflagger.com/yupform/2732873" id="2732873form" method="POST">
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Message</label>
        <input type="text" name="message">
        <input type="hidden" name="_next" value="https://yuphub.com?mailflagger=hello">
        <button type="submit">Submit</button>
    </form>

@endsection
