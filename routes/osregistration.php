<?php

Route::get(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('osregistration::backend.osregistration@index');
});

Route::put(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('settings::backend.settings@update');
});