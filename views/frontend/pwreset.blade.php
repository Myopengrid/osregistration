<?php themes\add_asset('ft_password_pages.css', 'mod: osregistration/css', array(), 'page') ?>
<div class="row">
    <div class="span8">
        <div id="auth-login-container">
            {{ \Form::open(URL::base().'/osregistration/pwreset', 'POST', array('class' => 'form-signin' )) }}

                {{ \Form::token() }}
                
                <h2 class="form-signin-heading">Password Reset</h2>
                <div class="control-group {{ $errors->has('email') ? 'error' : '' }}">
                    <div class="controls">
                        {{ \Form::text('email', Input::old('email'), array('class' => 'input-block-level', 'placeholder' => 'Enter your email address' )) }}

                        <span class="help-inline">{{ $errors->has('email') ? $errors->first('email', '<div class="auth-login-error">:message</div>') : '' }}</span>
                    </div>
                </div>
                {{ \Form::button('Submit', array('class' => 'btn btn-large btn-primary')) }}

            {{ \Form::close() }}
        </div>
    </div>
</div>