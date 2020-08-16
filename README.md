# YupForms
Flexible and reliable form collection. Yupforms is headless, independent of frontend form implementation.

Collect submissions for your application forms like contact, feedback, subscribe, etc. regardless of your
frontend framework or amount of fields in form.
 
## Installation
`composer require srg\yupforms`

#### Database
`php artisan migrate`

## Admin
YupForms Admin comes with CRUD functionality for forms(YupForm) and form submissions (YupFormData).
The admin frontend was built with bootstrap, jquery and datatables.net.

#### Route
Yupforms route /yupforms/yupform


#### Events
There are two events available to listen for when a form is submitted.  YupFormSubmissionAccepted when a form is 
saved even if it is flagged and YupFormSubmissionRejected when a form submission is not saved at all.

## How to Use:
Each form has a public action url for form submissions, a sample HTML form and Ajax form generated.
Use an example form as a starting point and add the needed fields.

Each YupForm has settings.  The form must be enabled to accept form submissions.

In addition, there is a download submissions as CSV function.

## License 
YupForms is open-sourced software licensed under the MIT license.
