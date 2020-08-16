<?php

//doesn't require _token since can be submitted from other sites
Route::get('yupform/thank-you', 'YupForms\Http\YupFormController@submitNext');
Route::post('yupform/{publicId}', 'YupForms\Http\YupFormController@submit');

Route::group(['middleware' => ['web']], function () {
    Route::get('yupforms/test/{publicId}', 'YupForms\Http\YupFormController@testPage');
    Route::get('yupforms/form-list', 'YupForms\Http\YupFormController@formIndexList');
    Route::get('yupforms/yupform/{yupform}/download-csv', 'YupForms\Http\YupFormController@downloadCsv');
    Route::resource('yupforms/yupform', 'YupForms\Http\YupFormController');

    Route::get('yupforms/formdata-list', 'YupForms\Http\YupFormDataController@formDataList');
    Route::get('yupforms/formdata/{formdata}/toggle-flag', 'YupForms\Http\YupFormDataController@toggleFlag');
    Route::get('yupforms/formdata/{formdata}', 'YupForms\Http\YupFormDataController@show');
});



