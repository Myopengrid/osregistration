<div style="margin-top:25px;" class="row">
    <div class="span12">
        {{Form::open_for_files( URL::base() .'/'.ADM_URI.'/osregistration/avatars', 'POST', array('class' => 'form-horizontal'))}}
        <div style="display:none">
            {{Form::token()}}
            {{ Form::hidden('slug', Input::old('slug', '')) }}
        </div>
        <div class="form_inputs">

            <div class="control-group {{ $errors->has('image') ? 'error' : '' }}">
                <label for="image" class="control-label">{{ Lang::line('osregistration::lang.Image')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::file('image') }}
                    <!-- <span class="help-inline">{{ $errors->has('image') ? $errors->first('image') : '' }}</span> -->
                </div>
            </div>

            <div class="control-group {{ $errors->has('name') ? 'error' : '' }}">
                <label for="name" class="control-label">{{ Lang::line('osregistration::lang.Name')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::text('name', Input::old('name', '')) }}
                    <span class="required-icon"></span>
                    <span class="help-inline">{{ $errors->has('name') ? $errors->first('name') : '' }}</span>
                </div>
            </div>

            <div class="control-group {{ $errors->has('slug') ? 'error' : '' }}">
                <label for="slug" class="control-label">{{ Lang::line('osregistration::lang.Slug')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::text('slug', Input::old('slug', '')) }}
                    <span class="required-icon"></span>
                    <span class="help-inline">{{ $errors->has('slug') ? $errors->first('slug') : '' }}</span>
                </div>
            </div>

            <div class="control-group {{ $errors->has('status') ? 'error' : '' }}">
                <label for="status" class="control-label">{{ Lang::line('osregistration::lang.Status')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::select('status', array(0 => Lang::line('osregistration::lang.Disabled')->get(ADM_LANG), 1 => Lang::line('osregistration::lang.Enabled')->get(ADM_LANG)), Input::old('status', 1)) }}
                    <span class="help-inline">{{ $errors->has('status') ? $errors->first('status') : '' }}</span>
                </div>
            </div>

             <div class="control-group {{ $errors->has('description') ? 'error' : '' }}">
                <label for="description" class="control-label">{{ Lang::line('osregistration::lang.Description')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::text('description', Input::old('description', '')) }}
                    <span class="help-inline">{{ $errors->has('description') ? $errors->first('description') : '' }}</span>
                </div>
            </div>

            <div class="control-group {{ $errors->has('clone') ? 'error' : '' }}">
                <label for="clone" class="control-label">{{ Lang::line('osregistration::lang.Clone From')->get(ADM_LANG) }}</label>
                <div class="controls">
                    {{ Form::select('clone', $clone_from) }}
                    <span class="help-inline">{{ $errors->has('clone') ? $errors->first('clone') : '' }}</span>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ URL::base() .'/'.ADM_URI}}/osregistration/avatars" class="btn">{{ __('osregistration::lang.Cancel')->get(ADM_LANG) }}</a> 
            <button type="submit" name="btnAction" value="create" class="btn btn-primary">
                <span>{{ __('osregistration::lang.Create')->get(ADM_LANG) }}</span>
            </button>
        </div>
        {{Form::close()}}
    </div>
</div>