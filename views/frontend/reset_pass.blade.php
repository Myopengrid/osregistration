<?php themes\add_asset('ft_password_pages.css', 'mod: osregistration/css', array(), 'page') ?>
<div class="row">
    <div class="span8">
        <div id="auth-login-container">
            {{ \Form::open(URL::base().'/osregistration/reset_pass', 'POST', array('class' => 'form-signin' )) }}

                {{ \Form::token() }}
                {{ \Form::hidden('user_id', $user_id) }}
                {{ \Form::hidden('code', $code) }}
                
                <h2 class="form-signin-heading">New Password</h2>
                
                <div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
                    <div class="controls">
                        {{ \Form::password('password', array('class' => 'input-block-level', 'placeholder' => 'Enter new password' )) }}

                        <span class="help-inline">{{ $errors->has('password') ? $errors->first('password', '<div class="auth-login-error">:message</div>') : '' }}</span>
                    </div>
                </div>

                <div class="control-group {{ $errors->has('password_confirmation') ? 'error' : '' }}">
                    <div class="controls">
                        {{ \Form::password('password_confirmation', array('class' => 'input-block-level', 'placeholder' => 'Confirm new password' )) }}

                        <span class="help-inline">{{ $errors->has('password_confirmation') ? $errors->first('password_confirmation', '<div class="auth-login-error">:message</div>') : '' }}</span>
                    </div>
                </div>
                {{ \Form::button('Save', array('class' => 'btn btn-large btn-primary')) }}

            {{ \Form::close() }}
        </div>
    </div>
</div>