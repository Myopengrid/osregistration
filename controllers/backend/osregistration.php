<?php

class Osregistration_Backend_Osregistration_Controller extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->data['section_bar'] = array(
            Lang::line('osregistration::lang.Settings')->get(ADM_LANG)    => URL::base().'/'.ADM_URI.'/osregistration',
            Lang::line('osregistration::lang.Avatars')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/osregistration/avatars',
            Lang::line('osregistration::lang.New Avatar')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/osregistration/avatars/new',
        );

        $this->data['bar'] = array(
            'title'       => Lang::line('osregistration::lang.OpenSim Registration')->get(ADM_LANG),
            'url'         => URL::base().'/'.ADM_URI.'/osregistration',
            'description' => Lang::line('osregistration::lang.Allow administrators to add custom avatars and mange the grid registration')->get(ADM_LANG),
        );
    }
    public function get_index()
    {
        $this->data['section_bar_active'] = Lang::line('osregistration::lang.Settings')->get(ADM_LANG);

         $this->data['settings'] = Settings\Model\Setting::where('module_slug', '=', 'osregistration')
                                    ->order_by('order', 'asc')
                                    ->get();

        return $this->theme->render('osregistration::backend.osregistration.index', $this->data);
    }
}