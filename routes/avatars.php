<?php

Route::get(ADM_URI.'/(:bundle)/avatars', function()
{
    return Controller::call('osregistration::backend.avatars@index');
});

Route::get(ADM_URI.'/(:bundle)/avatars/new', function()
{
    return Controller::call('osregistration::backend.avatars@new');
});

Route::post(ADM_URI.'/(:bundle)/avatars', function()
{
    return Controller::call('osregistration::backend.avatars@create');
});

Route::get(ADM_URI.'/(:bundle)/avatars/(:num)/edit', function($id)
{
    return Controller::call('osregistration::backend.avatars@edit', array($id));
});

Route::put(ADM_URI.'/(:bundle)/avatars/(:num)', function($id)
{
    return Controller::call('osregistration::backend.avatars@update', array($id));
});

Route::delete(ADM_URI.'/(:bundle)/avatars/(:num)', function($id)
{
    return Controller::call('osregistration::backend.avatars@destroy', array($id));
});

Route::put(ADM_URI.'/(:bundle)/avatars/sort', function()
{
    return Controller::call('osregistration::backend.avatars@sort');
});
