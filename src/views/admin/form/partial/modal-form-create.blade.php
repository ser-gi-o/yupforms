<?php
/**
 * Create form uses a bootstrap modal
 */
?>


<!-- Modal -->
<div class="modal fade"
     id="createYupFormModal"
     data-backdrop="static"
     data-keyboard="false"
     tabindex="-1"
     role="dialog"
     aria-labelledby="createYupFormModalLabel"
     aria-hidden="true"
>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createYupFormModalLabel">Create Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ action('\YupForms\Http\YupFormController@store') }}" id="createYupForm" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label text-right">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" value="" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="description" class="col-sm-2 col-form-label text-right">Description</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="description">
                        </div>
                    </div>

                    <div class="p-4 float-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
