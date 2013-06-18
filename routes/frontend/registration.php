<?php

// Account activation link
Route::get('(:bundle)/activate/(:any)', function($activation_code)
{
    return Controller::call('osregistration::frontend.registration@activate', array($activation_code));
});

// shows password reset form
Route::get('(:bundle)/pwreset', function()
{
    return Controller::call('osregistration::frontend.registration@pwreset');
});

// handles submit of password reset form
Route::post('(:bundle)/pwreset', function()
{
    return Controller::call('osregistration::frontend.registration@pwreset');
});

// handles link sent to email to reset password
// osregistration/reset_pass/{{ user:id }}/{{ forgotten_password_code }}
Route::get('(:bundle)/reset_pass/(:num)/(:any)', function($user_id, $code)
{
    return Controller::call('osregistration::frontend.registration@reset_pass', array($user_id, $code));
});

Route::post('(:bundle)/reset_pass', function()
{
    return Controller::call('osregistration::frontend.registration@reset_pass');
});

Route::get('/ossignup', function()
{
    return Controller::call('osregistration::frontend.registration@index');
});

Route::post('/ossignup', function()
{
    return Controller::call('osregistration::frontend.registration@create');
});