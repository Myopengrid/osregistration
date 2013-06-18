<?php themes\add_asset('bk_osregistration.js', 'mod: osregistration/js', array('scripts'), 'footer') ?>

@if(isset($custom_avatars) and !empty($custom_avatars))
<div class="row" style="margin-top:25px;">
    <div class="span12">
            {{ View::make('osregistration::backend.avatars.partials.avatars_table', array('posts' => $custom_avatars)) }}
    </div>
</div>
@else
<div class="row" style="margin-top:25px;">
    <div class="span12">
        
        <div class="offset4">
            <br />
            {{HTML::link_to_action(ADM_URI.'/'.'osregistration/avatars@new', 'New Custom Avatar', array(), array('class' => 'btn btn-primary')) }}
        </div>
    </div>
</div>
@endif
