<?php
$formId = $publicId . 'form';
$formMessageId = $publicId . 'form-message';
?>
<form action="{{ action('\YupForms\Http\YupFormController@submit', ['publicId' => $publicId]) }}" id="{{ $formId }}" method="POST">
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Message</label>
    <input type="text" name="message">
    <button type="submit">Submit</button>
</form>
<div id="{{ $formMessageId }}"></div>


<script>
    window.addEventListener("DOMContentLoaded", function() {
        // form elements
        var form = document.getElementById("{{ $formId }}");
        var message = document.getElementById("{{ $formMessageId }}");

        function response(status){
            if (status === 200) {
                //success
                form.reset();
                form.style = "display: none";
                message.innerHTML = "Thank You!";

            } else {
                //error
                message.innerHTML = "There was a problem.";
            }
        }

        // form submission
        form.addEventListener("submit", function(ev) {
            ev.preventDefault();
            send(form, response);
        });
    });

    // send form request
    function send(form, response) {
        var xhr = new XMLHttpRequest();
        var data = new FormData(form);

        xhr.open(form.method, form.action);
        xhr.setRequestHeader("Accept", "application/json");
        xhr.onreadystatechange = function() {
            if (xhr.readyState !== XMLHttpRequest.DONE)
                return;

            response(xhr.status, xhr.response, xhr.responseType);
        };

        xhr.send(data);
    }

</script>
