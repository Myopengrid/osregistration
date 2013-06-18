<?php themes\add_asset('ft_osregistration.css', 'mod: osregistration/css', array(), 'page') ?>
<?php themes\add_asset('ft_osregistration.js', 'mod: osregistration/js', array('scripts'), 'footer') ?>


<div class="row">
    <div class="span11">
        <div id="auth-login-container">
            {{ \Form::open(URL::base().'/ossignup', 'POST', array('class' => 'form-signin' )) }}

                {{ \Form::token() }}
                {{ \Form::hidden('avatar_appearance', Input::old('avatar_appearance', '0'), array('id' => 'avatar_appearance')) }}
                
                <?php if(isset($avatars) and !empty($avatars)): ?>
                <h3 class="form-signin-heading">Click on the image to select your avatar.</h3>
                <?php if($errors->has('avatar_appearance')): ?>
                <div class="alert alert-error fade in">
              <button type="button" class="close" data-dismiss="alert">×</button>{{ $errors->has('avatar_appearance') ? $errors->first('avatar_appearance') : '' }}</div>
                <?php endif ?>

                
                    <div class="avatars-container">
                        <div class="row-fluid">
                            <ul class="thumbnails">
                            <?php foreach ($avatars as $avatar): ?>
                                <li class="span3">
                                    <a class="thumbnail">
                                        <?php echo \Thumbnails\Html::thumbnail($avatar->image_absolute_path.DS.$avatar->image_full_name,
                                            array(
                                                'mode' => 'outbound',
                                                'size' => '180x260',
                                                'alt'  => 'avatar-'.$avatar->id,
                                                'attr' => array(
                                                    'data-avatar-id' => $avatar->id, 
                                                    'style'          => 'width: 260px; height: 180px;'),
                                                )
                                            ) 
                                        ?>
                                    </a>
                                </li>
                                {{ cc($avatar) }}
                            <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                <?php else: ?>
                <h3 class="form-signin-heading">Grid Sign Up</h3>
                <br/>
                <?php endif ?>
                
                <div class="control-group {{ $errors->has('email') ? 'error' : '' }}">
                    <div class="controls">
                        {{ \Form::text('email', Input::old('email'), array('class' => 'input-block-level', 'placeholder' => 'Email address' )) }}

                        <span class="help-inline">{{ $errors->has('email') ? $errors->first('email', '<div class="auth-login-error">:message</div>') : '' }}</span>
                    </div>
                </div>

                <div class="control-group {{ $errors->has('username') ? 'error' : '' }}">
                    <div class="controls">
                        {{ \Form::text('username', Input::old('username'), array('class' => 'input-block-level', 'placeholder' => 'Username' )) }}

                        <span class="help-inline">{{ $errors->has('username') ? $errors->first('username', '<div class="auth-login-error">:message</div>') : '' }}</span>
                    </div>
                </div>


                <div class="control-group {{ $errors->has('avatar_first_name') ? 'error' : '' }}">
                    <div class="controls">
                        {{ \Form::text('avatar_first_name', Input::old('avatar_first_name'), array('class' => 'input-block-level', 'placeholder' => 'Desired Avatar First Name' )) }}

                        <span class="help-inline">{{ $errors->has('avatar_first_name') ? $errors->first('avatar_first_name', '<div class="auth-login-error">:message</div>') : '' }}</span>
                    </div>
                </div>

                <div class="control-group {{ $errors->has('avatar_last_name') ? 'error' : '' }}">
                    <div class="controls">
                        {{ \Form::text('avatar_last_name', Input::old('avatar_last_name'), array('class' => 'input-block-level', 'placeholder' => 'Desired Avatar Last Name' )) }}

                        <span class="help-inline">{{ $errors->has('avatar_last_name') ? $errors->first('avatar_last_name', '<div class="auth-login-error">:message</div>') : '' }}</span>
                    </div>
                </div>

                <div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
                    <div class="controls">
                        {{ \Form::password('password', array('class' => 'input-block-level', 'placeholder' => 'Password')) }}

                        <span class="help-inline">{{ $errors->has('password') ? $errors->first('password', '<div class="auth-login-error">:message</div>') : '' }}</span>
                    </div>
                </div>

                <div class="control-group {{ $errors->has('password_confirmation') ? 'error' : '' }}">
                    <div class="controls">
                        {{ \Form::password('password_confirmation', array('class' => 'input-block-level', 'placeholder' => 'Password Confirmation')) }}

                        <span class="help-inline">{{ $errors->has('password_confirmation') ? $errors->first('password_confirmation', '<div class="auth-login-error">:message</div>') : '' }}</span>
                    </div>
                </div>

                {{ \Form::button('Sign Up', array('class' => 'btn btn-large btn-success')) }}

            {{ \Form::close() }}

        </div>
    </div>
</div>