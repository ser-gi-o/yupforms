<form action={{ action('\YupForms\Http\YupFormController@submit', ['publicId' => $publicId]) }}" id="{{ $publicId }}form" method="POST">
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Message</label>
    <input type="text" name="message">
    <button type="submit">Submit</button>
</form>
