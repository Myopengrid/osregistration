<?php 

class Osregistration_Schema_Task {

    public function __construct()
    {
        // the required modules must be enabled already
        // at this point
        Bundle::register('email');
        Bundle::start('email');

        Bundle::register('settings');
        Bundle::start('settings');

        Bundle::register('modules');
        Bundle::start('modules');

        Bundle::register('navigation');
        Bundle::start('navigation');
    }

    public function install()
    {
        //
        // EMAIL TEMPLATES
        // 
        $activation_email = array(
            'name'        => 'Grid Activation Email',
            'slug'        => 'osregistration_activation',
            'description' => 'The email which contains the grid activation code that is sent to a new user',
            'subject'     => '{{ settings:site_name }} - Account Activation',
            'body'        => File::get(dirname(__FILE__).DS.'data'.DS.'activation_template_body.html'),
            'lang'        => 'en',
            'type'        => 'html',
            'module'      => 'osregistration',
            'is_default'  => 1,
            'is_core'     => 1
        );
        $at = Email\Model\Template::create($activation_email);

        $forgotten_password = array(
            'name'        => 'Grid Forgotten Password Email',
            'slug'        => 'osregistration_forgotten_password',
            'description' => 'The email that is sent containing a password reset code',
            'subject'     => '{{ settings:site_name }} - Forgotten Password',
            'body'        => File::get(dirname(__FILE__).DS.'data'.DS.'forgotten_template_body.html'),
            'lang'        => 'en',
            'type'        => 'html',
            'module'      => 'osregistration',
            'is_default'  => 1,
            'is_core'     => 1
        );
        $fp = Email\Model\Template::create($forgotten_password);
        
        $registered = array(
            'name'        => 'Grid New User Registered',
            'slug'        => 'osregistration_registered',
            'description' => 'Email sent to the site contact e-mail when a new user registers',
            'subject'     => 'A New user registered to {{ settings:site_name }}',
            'body'        => File::get(dirname(__FILE__).DS.'data'.DS.'registered_template_body.html'),
            'lang'        => 'en',
            'type'        => 'html',
            'module'      => 'osregistration',
            'is_default'  => 1,
            'is_core'     => 1
        );
        $re = Email\Model\Template::create($registered);
        
        $welcome_email = array(
            'name'        => 'Grid Welcome Email',
            'slug'        => 'osregistration_welcome_email',
            'description' => 'The email that is sent to welcome user after account activation',
            'subject'     => '{{ settings:site_name }} - Welcome!',
            'body'        => File::get(dirname(__FILE__).DS.'data'.DS.'welcome_template_body.html'),
            'lang'        => 'en',
            'type'        => 'html',
            'module'      => 'osregistration',
            'is_default'  => 1,
            'is_core'     => 1
        );
        $we = Email\Model\Template::create($welcome_email);

        //
        // REGISTRATION SETTINGS
        //
        
        $registration_module = \Modules\Model\Module::where_slug('osregistration')->first();
        
        $conf_email = array(
            'title'       => 'Grid Confirmation Email',
            'slug'        => 'osregistration_email_template',
            'description' => 'If email confirmation is enabled this is the email template that will be sent to the user on signup',
            'type'        => 'select',
            'default'     => $at->id,
            'value'       => $at->id,
            'options'     => 'func:email\get_email_templates',
            'class'       => '',
            'section'     => '',
            'validation'  => '',
            'is_gui'      => '',
            'module_slug' => 'osregistration',
            'module_id'   => $registration_module->id,
        );
        $conf_email = Settings\Model\Setting::create($conf_email);

        $registration_disabled = array(
            'title'       => 'Grid Registration Disabled',
            'slug'        => 'osregistration_disabled',
            'description' => 'Enables or disable registration into the application',
            'type'        => 'select',
            'default'     => 'no',
            'value'       => 'no',
            'options'     => '{"yes":"Yes","no":"No"}',
            'class'       => '',
            'section'     => '',
            'validation'  => '',
            'is_gui'      => '',
            'module_slug' => 'osregistration',
            'module_id'   => $registration_module->id,
        );
        $registration_disabled = Settings\Model\Setting::create($registration_disabled);

        $registration_pwreset_email_template = array(
            'title'       => 'Grid Password Reset Email Template',
            'slug'        => 'osregistration_pwreset_email_template',
            'description' => 'Email template to be used upon password reset',
            'type'        => 'select',
            'default'     => $fp->id,
            'value'       => $fp->id,
            'options'     => 'func:email\get_email_templates',
            'class'       => '',
            'section'     => '',
            'validation'  => '',
            'is_gui'      => '',
            'module_slug' => 'osregistration',
            'module_id'   => $registration_module->id,
        );
        $registration_pwreset_email_template = Settings\Model\Setting::create($registration_pwreset_email_template);

        $registration_confirmation_required = array(
            'title'       => 'Grid Confirmation Required',
            'slug'        => 'osregistration_confirmation_required',
            'description' => 'User must confirm email to activate account If set to no user will be able to login after signup',
            'type'        => 'select',
            'default'     => 'yes',
            'value'       => 'yes',
            'options'     => '{"yes":"Yes","no":"No"}',
            'class'       => '',
            'section'     => '',
            'validation'  => '',
            'is_gui'      => '',
            'module_slug' => 'osregistration',
            'module_id'   => $registration_module->id,
        );
        $registration_confirmation_required = Settings\Model\Setting::create($registration_confirmation_required);

        $registration_admin_new_user = array(
            'title'       => 'Grid Admin Registration Email', 
            'slug'        => 'osregistration_email_admin_on_registration', 
            'description' => 'Send an email to Admin when a new user registers', 
            'type'        => 'select', 
            'default'     => 'yes', 
            'value'       => 'yes', 
            'options'     => '{"yes":"Yes","no":"No"}', 
            'class'       => '', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => '1', 
            'module_slug' => 'osregistration', 
            'module_id'   => $registration_module->id, 
            'order'       => '999', 
        );
        $registration_email_admin_user = Settings\Model\Setting::create($registration_admin_new_user);

        $admin_registration_email_template = array(
            'title'       => 'Grid User Registered Email Template',
            'slug'        => 'osregistration_admin_registration_email_template',
            'description' => 'Template to be used if the admin registration email is enabled',
            'type'        => 'select',
            'default'     => $re->id,
            'value'       => $re->id,
            'options'     => 'func:email\get_email_templates',
            'class'       => '',
            'section'     => '',
            'validation'  => '',
            'is_gui'      => '',
            'module_slug' => 'osregistration',
            'module_id'   => $registration_module->id,
        );
        $admin_registration_email = Settings\Model\Setting::create($admin_registration_email_template);

        //
        // EMAIL NAVIGATION LINK
        // 

        $sidebar = Navigation\Model\Group::where_slug('sidebar')->first();

        // Add links to sidebar navigation group
        $sidebar_signup_link = array(
            'title'         => 'Grid Sign Up',
            'module_id'     => $registration_module->id,
            'page_id'       => '0',
            'group_id'      => $sidebar->id,
            'parent'        => '0',
            'link_type'     => 'uri',
            'url'           => '',
            'uri'           => 'ossignup',
            'target'        => '',
            'order'         => '2',
            'restricted_to' => '0',
            'class'         => '',
            'is_core'       => '1',
        );
        $sidebar_link = Navigation\Model\Link::create($sidebar_signup_link);

        $header = Navigation\Model\Group::where_slug('header')->first();
        $header_signup_link = array(
            'title'         => 'Grid Sign Up',
            'module_id'     => $registration_module->id,
            'page_id'       => '0',
            'group_id'      => $header->id,
            'parent'        => '0',
            'link_type'     => 'uri',
            'url'           => '',
            'uri'           => 'ossignup',
            'target'        => '',
            'order'         => '2',
            'restricted_to' => '0',
            'class'         => '',
            'is_core'       => '1',
        );
        $header_link = Navigation\Model\Link::create($header_signup_link);

    }

    public function uninstall()
    {
        $module = \Modules\Model\Module::where_slug('osregistration')->first();

        //
        // REMOVE EMAIL TEMPLATES
        // 
        $email_templates = Email\Model\Template::where('module', '=', 'osregistration')->get();
        
        if(isset($email_templates) and !empty($email_templates))
        {
            foreach ($email_templates as $template) 
            {
                $template->delete();
            }
        }

        //
        // REMOVE SETTINGS
        // 
        $settings = Settings\Model\Setting::where('module_slug', '=', 'osregistration')->get();
        
        if(isset($settings) and !empty($settings))
        {
            foreach ($settings as $setting) 
            {
                $setting->delete();
            }
        }

        //
        // REMOVE Navigation
        // 
        $navigation_links = Navigation\Model\Link::where('module_id', '=', $module->id)->get();
        
        if(isset($navigation_links) and !empty($navigation_links))
        {
            foreach ($navigation_links as $navigation_link) 
            {
                $navigation_link->delete();
            }
        }

        @File::rmdir(path('public').DS.'bundles'.DS.'osregistration');
    }

    public function __destruct()
    {
        Bundle::disable('email');
        Bundle::disable('settings');
        Bundle::disable('modules');
        Bundle::disable('navigation');
    }
}