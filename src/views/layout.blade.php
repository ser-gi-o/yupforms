<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" crossorigin="anonymous">
    <title>{{ $title ?? 'Yup Forms' }}</title>

    <style>
        #indexTable_wrapper input, #indexTable_wrapper select {
            border: 1px solid #999;
            padding: 4px;
        }

        #indexTable tbody tr:hover  {
            background-color: rgb(244, 248, 253);
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 300!important;
        }
        label {
            color: rgba(0,0,0,.6);
        }
    </style>
    @stack('head')
</head>

<body>

<div class="container" id="app">
    <h1>{{ $title ?? '' }}</h1>

    @if (isset($errors) &&  $errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (Session::has('alerts'))
        <?php
        $alerts = Session::pull('alerts');
        ?>
        @foreach ($alerts as $alert)
            <div class="row">
                <div class="offset-3 col-sm-6">
                    {{-- set correctly --}}
                    @if (isset($alert['type']))
                        <div class="alert alert-{{ $alert['type'] }}">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            {!! $alert['message'] !!}
                        </div>
                    @else
                        <div class="">
                            {{ 'Alert mis-formatted: ' }}
                            <span class="text-info">{{ print_r($alert, true) }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

    @yield('content')

</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

@stack('js')

</body>
</html>
