<h3 class="pb-4">Settings</h3>
<form action="{{ action('\YupForms\Http\YupFormController@update', ['yupform' => $yupForm->id])  }}" method="post">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">


    <div class="form-group">
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="ccb1" name="status" value="1"  {{ $yupForm->status ? 'checked' : '' }}>
            <label class="custom-control-label" for="ccb1">Form Enabled</label>
        </div>
    </div>

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" name="name" value="{{ $yupForm->name }}">
    </div>

    <div class="form-group">
        <label for="name">Description</label>
        <input type="text" class="form-control" name="description" value="{{ $yupForm->description }}">
    </div>

    <div class="form-group">
        <label for="name">Host</label>
        <input type="text" class="form-control" name="host" value="{{ $yupForm->host }}" placeholder="">
        <small id="" class="form-text text-muted">Restrict submissions to this host. ie example.com</small>
    </div>

    {{--
    <div class="form-group">
        <label for="name">reCaptcha</label>
        <input type="text" class="form-control" name="recaptcha" value="{{ $yupForm->recaptcha }}">
        <small id="" class="form-text text-muted">custom recaptcha</small>
    </div>
    --}}

    <div class="text-center">
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>
