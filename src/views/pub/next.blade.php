@extends('yupforms::layout')

@push('head')

@endpush

@push('js')
<script>
    document.getElementsByTagName("BODY")[0].style = 'background-color: #f4f8fd';
</script>
@endpush

@section('content')

    <div class="row mt-4">
        <div class="offset-3 col-6">
        <div class="card">
            <div class="card-body">
                <h2>{{ $message ?? 'thank you!' }}</h2>
                <div class="pt-4 pb-4">
                    <a href="{{ $back ?? '' }}" class="text-secondary">Return to page </a>
                </div>
            </div>
        </div>
        </div>
    </div>
@endsection
