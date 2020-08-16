<h2 class="">Embed</h2>

<div class="container m-2 pb-4" id="#html">
    <div class="">
        <h4 id="#html">
            Form Action Url
        </h4>
        <input disabled value="{{ action('\YupForms\Http\YupFormController@submit', ['publicId' => $yupForm->public_id]) }}"
               class="form-control"
        >
    </div>
</div>

<div class="container m-2" id="#html">
    <div class="">
        <h4 id="#html">
            Html
            <span class="ml-3" style="font-size: .7em;">
                (<a href="{{ action('\YupForms\Http\YupFormController@testPage', ['publicId' => $yupForm->public_id]) }}"
                    target="_blank"
                    class=""
                >
                    Test Form <i class="fas fa-external-link-alt small"></i>
                </a>)
            </span>
        </h4>

        <label>
            This is an example.  Add as many fields as necessary for your form.
        </label>
        <textarea name="text"
                  {{-- oninput='this.style.height = "";this.style.height = this.scrollHeight + 3 + "px"' --}}
                  style="width: 100%; background-color: #e9ecef;"
                  rows="8"
                  readonly
        >@include('yupforms::admin.form.template.html', ['publicId' => $yupForm->public_id])</textarea>
    </div>
</div>

<div class="container m-2" id="#html">
    <div class="">
        <h4 id="#ajax">
            Javascript ajax
            <span class="ml-3" style="font-size: .7em;">
                (<a href="{{ action('\YupForms\Http\YupFormController@testPage', ['publicId' => $yupForm->public_id, 'type' => 'ajax']) }}"
                    target="_blank"
                    class=""
                >
                    Test Form <i class="fas fa-external-link-alt small"></i>
                </a>)
            </span>
        </h4>
        <script>
            function expand() {

            }
        </script>
        <label>
            This is an ajax example.  Form submission does not leave current page.
            <a href="" class="text-info small"
               onclick="event.preventDefault(); document.getElementById('jsTextArea').style.height = document.getElementById('jsTextArea').scrollHeight + 'px'"
            >
                <i class="fas fa-expand"></i> expand
            </a>
        </label>
        <textarea id="jsTextArea"
                  style="width: 100%; background-color: #e9ecef;"
                  rows="8"
                  readonly
        >@include('yupforms::admin.form.template.ajax', ['publicId' => $yupForm->public_id])</textarea>
    </div>
</div>



