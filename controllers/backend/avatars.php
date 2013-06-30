<?php

class Osregistration_Backend_Avatars_Controller extends Admin_Controller {

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
        $this->data['section_bar_active'] = Lang::line('osregistration::lang.Avatars')->get(ADM_LANG);
        
        $this->data['custom_avatars'] = Osregistration\Model\Avatar::order_by('order', 'asc')->order_by('updated_at', 'desc')->get();

        return $this->theme->render('osregistration::backend.avatars.index',$this->data);
    }

    public function get_new()
    {
        $this->data['section_bar_active'] = Lang::line('osregistration::lang.New Avatar')->get(ADM_LANG);
        $grid_users                       = Opensim\Model\Os\UserAccount::get();
        $this->data['clone_from']         = array(0 => 'Please Select');
        if(isset($grid_users) and !empty($grid_users))
        {
            foreach ($grid_users as $user) 
            {
                $this->data['clone_from'][$user->principalid] = $user->firstname.' '.$user->lastname;
            }
        }

        return $this->theme->render('osregistration::backend.avatars.new',$this->data);
    }

    public function post_create()
    {
        $post_data = Input::all();
        
        $messages = array(
            'check_clone' => 'The appareance for this avatar was not found',
        );

        Validator::register('check_clone', function($attribute, $value, $parameters)
        {
            if(ctype_digit($value)) return false;

            $avatar_appareance = Opensim\Model\Os\Avatar::where('PrincipalID', '=', $value)->get();

            if(isset($avatar_appareance) and !empty($avatar_appareance))
            {
                return true;
            }

            return false;
        });

        $rules = array(
            'name'  => 'required|max:100',
            'slug'  => 'required|alpha_dash|unique:osregistration_avatar',
            'clone' => 'required|check_clone',
            'image' => 'required|image',
        );

        $validation = Validator::make($post_data, $rules, $messages);

        if ($validation->passes())
        {
            $custom_avatar = new Osregistration\Model\Avatar;
            $custom_avatar->name        = $post_data['name'];
            $custom_avatar->slug        = $post_data['slug'];
            $custom_avatar->description = $post_data['description'];
            $custom_avatar->uuid        = $post_data['clone'];
            $custom_avatar->status      = $post_data['status'];

            $image_absolute_path = path('public').'bundles'.DS.'osregistration'.DS.'avatar'.DS.'images';
            $image_name          = Opensim\UUID::random();
            $image_ext           = '.'.get_file_extension($post_data['image']['name']);
            $image_full_name     =  $image_name.$image_ext;
            $image = Input::upload('image', $image_absolute_path, $image_full_name);
            if($image)
            {
                if(isset($image) and !empty($image))
                {
                    $custom_avatar->image_name          = $image_full_name;
                }
            }

            $custom_avatar->save();

            $this->save_appearance_items($post_data, $custom_avatar);

            $this->data['message']      = __('osregistration::lang.Custom avatar was successfully created')->get(ADM_LANG);
            $this->data['message_type'] = 'success';
            
            return Redirect::to(ADM_URI.'/osregistration/avatars')->with($this->data);
            
        }

        return Redirect::to(ADM_URI.'/osregistration/avatars/new')
                            ->with('errors', $validation->errors)
                            ->with_input();
    }

    public function get_edit($id)
    {
        $this->data['section_bar'] = array(
            Lang::line('osregistration::lang.Settings')->get(ADM_LANG)    => URL::base().'/'.ADM_URI.'/osregistration',
            Lang::line('osregistration::lang.Avatars')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/osregistration/avatars',
            Lang::line('osregistration::lang.New Avatar')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/osregistration/avatars/new',
            Lang::line('osregistration::lang.Edit')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/osregistration/avatars/'.$id.'/edit',
        );
        $this->data['section_bar_active'] = Lang::line('osregistration::lang.Edit')->get(ADM_LANG);

        $this->data['avatar']     = Osregistration\Model\Avatar::find($id);
        $grid_users               = Opensim\Model\Os\UserAccount::get();
        $this->data['clone_from'] = array(0 => 'Please Select');
        if(isset($grid_users) and !empty($grid_users))
        {
            foreach ($grid_users as $user) 
            {
                $this->data['clone_from'][$user->principalid] = $user->firstname.' '.$user->lastname;
            }
        }

        return $this->theme->render('osregistration::backend.avatars.edit', $this->data);
    }

    public function put_update($id)
    {
        $post_data = Input::all();

        $messages = array(
            'check_clone' => 'The appareance for this avatar was not found',
        );

        Validator::register('check_clone', function($attribute, $value, $parameters)
        {
            if(ctype_digit($value)) return false;

            $avatar_appareance = Opensim\Model\Os\Avatar::where('PrincipalID', '=', $value)->get();

            if(isset($avatar_appareance) and !empty($avatar_appareance))
            {
                return true;
            }

            return false;
        });

        $rules = array(
            'name'  => 'required|max:100',
            'slug'  => 'required|alpha_dash|unique:osregistration_avatar,slug,'.$id,
            'clone' => 'required|check_clone',
        );

        $validation = Validator::make($post_data, $rules, $messages);

        if ($validation->passes())
        {
            $custom_avatar = Osregistration\Model\Avatar::find($id);
            $custom_avatar->name        = $post_data['name'];
            $custom_avatar->slug        = $post_data['slug'];
            $custom_avatar->description = $post_data['description'];
            $custom_avatar->uuid        = $post_data['clone'];
            $custom_avatar->status      = $post_data['status'];

            if( isset($post_data['image']['error']) and $post_data['image']['error'] == 0)
            {
                $image_absolute_path = path('public').'bundles'.DS.'osregistration'.DS.'avatar'.DS.'images';
                
                // Remove old image
                File::delete(path('public').$image_absolute_path.DS.$custom_avatar->image_name);
                
                $image_name          = Opensim\UUID::random();
                $image_ext           = '.'.get_file_extension($post_data['image']['name']);
                $image_full_name     =  $image_name.$image_ext;
                $image = Input::upload('image', $image_absolute_path, $image_full_name);
                if($image)
                {
                    if(isset($image) and !empty($image))
                    {
                        $custom_avatar->image_name          = $image_full_name;
                    }
                }
            }

            $custom_avatar->save();

            $this->save_appearance_items($post_data, $custom_avatar);

            $this->data['message']      = __('osregistration::lang.Custom avatar was successfully updated')->get(ADM_LANG);
            $this->data['message_type'] = 'success';
            
            return Redirect::to(ADM_URI.'/osregistration/avatars')->with($this->data);
        }

        return Redirect::to(ADM_URI.'/osregistration/avatars/'.$id.'/edit')
                            ->with('errors', $validation->errors);
    }

    public function delete_destroy($id)
    {
        $custom_avatar = \Osregistration\Model\Avatar::find($id);

        if(isset($custom_avatar) and !empty($custom_avatar))
        {
            // Remove Custom Avatar Image
            \File::delete(path('public').'bundles'.DS.'osregistration'.DS.'avatar'.DS.'images'.DS.$custom_avatar->image_full_name);
            
            // Remove thumbnail
            $paths = array(
                path('public').Config::get('thumbnails::options.image_path').DS.'180x260'.DS.'outbound-'.$custom_avatar->image_full_name,
                path('public').Config::get('thumbnails::options.image_path').DS.'100x100'.DS.'outbound-'.$custom_avatar->image_full_name,

            );
            Event::fire('thumbnails.delete', array($paths));
            
            // Delete Custom Avatar Appearance
            $custom_avatar->appearance()->delete();
            // Delete Custom Avatar Items
            $custom_avatar->items()->delete();
            // Delete Custom Avatar
            $custom_avatar->delete();

            $this->data['message']      = __('osregistration::lang.Custom avatar was successfully deleted')->get(ADM_LANG);
            $this->data['message_type'] = 'success';
        }
        else
        {
            $this->data['message']      = __('osregistration::lang.Custom avatar was not found')->get(ADM_LANG);
            $this->data['message_type'] = 'error';
        }

        return Redirect::to(ADM_URI.'/osregistration/avatars')->with($this->data);
    }

    public function put_sort()
    {
        $post_data = Input::get('order');
        
        if(isset($post_data) and !empty($post_data))
        {
            $order_items = explode(',', $post_data);
            foreach ($order_items as $order_position => $avatar_id)
            {
                $affected = \Osregistration\Model\Avatar::where_id($avatar_id)
                              ->update(array('order' => $order_position));
            }
            return;
        }
    }

    private function save_appearance_items($post_data, $custom_avatar)
    {
        $avatar_appareance = Opensim\Model\Os\Avatar::where('PrincipalID', '=', $post_data['clone'])->get();

        $avatar_item_ids = array();

        $attachmentIds = array();

        if(isset($avatar_appareance) and !empty($avatar_appareance))
        {
            $custom_avatar->appearance()->delete();

            foreach ($avatar_appareance as $parameter) 
            {
                // check if the value is an UUID
                // if it is it probaly is an attachment item
                if(\Opensim\UUID::is_valid($parameter->value))
                {
                    $avatar_item_ids[] = $parameter->value;
                }

                // check for wareables. they come as
                // Wearable n:n inventoryid:assetid
                // Wearable 5:0 e57affd5-29b4-4bce-8f10-8a6e11e4156b:00000000-38f9-1111-024e-222222111120
                // check for key and try to explode uuid's
                $uuids = explode(':', $parameter->value);
                if(count($uuids) == 2)
                {
                    if(\Opensim\UUID::is_valid($uuids[0]))
                    {
                        $avatar_item_ids[] = $uuids[0];
                    }
                }

                // Attachments come with the inventoryid
                // as value but I need the asset id because
                // when we are saving the appareance for 
                // the new user on CustomAvatar.php the 
                // new inventory will not have the same
                // inventory id. So here we exchange the inventory
                // id by its asset id and it will work fine.
                if(preg_match('/_ap_\d+/', $parameter->name))
                {
                    $attachmentIds[$parameter->name] = $parameter->value;
                }

                $custom_avatar->appearance()->insert(array('name' => $parameter->name, 'value' => $parameter->value));
            }
        }

        $avatar_items = null;
        if( !empty($avatar_item_ids))
        {
            // Remove all old items
            $custom_avatar->items()->delete();

            $avatar_items = Opensim\Model\Os\InventoryItem::where_in('inventoryID', $avatar_item_ids)->get();
            if(isset($avatar_items) and !empty($avatar_items))
            {
                foreach ($avatar_items as $inventoryItem) 
                {
                    $itemArray = $inventoryItem->to_array();
                    $avatar_item = new \Osregistration\Model\AvatarItem;
                    $avatar_item->fill($itemArray);
                    $avatar_item->avatar_id = $custom_avatar->id;
                    $avatar_item->save();

                    // On the Custom Avatar Appearance
                    // we need to update all the attachments
                    // from the inventoryid to its assetID
                    // or it will fail in the CustomAvatar.php
                    // since it gets all the assets ids to create
                    // the new appearence. If we leave it as inventoryid
                    // the attachment in the new inventory will have a diffetent
                    // inventoryid and it will fail to set the attachment to the new user
                    if(isset($attachmentIds) and !empty($attachmentIds))
                    {
                        $key = array_search($inventoryItem->inventoryid, $attachmentIds);
                        if(isset($key) and !empty($key))
                        {
                            $avatarAttachment = \Osregistration\Model\AvatarAppearance::where_name($key)->where_avatar_id($custom_avatar->id)->first();
                            $avatarAttachment->value = $inventoryItem->assetid;
                            $avatarAttachment->save();
                        }
                    }
                }
            }
        }
    }
}