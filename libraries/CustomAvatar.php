<?php namespace Osregistration;

use Opensim\UUID;
use Opensim\Model\Os\Avatar;
use Opensim\Model\Os\InventoryItem;

use Osregistration\Model\AvatarAppearance;

class CustomAvatar {

    private $inventoryItem;

    public function __construct(InventoryItem $inventoryItem)
    {
        // $this->accountUUID   = $accountUUID;
        // $this->avatarData    = $avatarData;
        $this->inventoryItem = $inventoryItem;
        // $this->appearance    = $avatar;
    }

    public function load_custom_items($items, $user, $account_inventory)
    {
        $itemsArray = array();
        $customFolderName = \Config::get('settings::core.osregistration_custom_folder_name', 'Starting Appearance');

        $parentFolder = $account_inventory[$customFolderName]['folderID'];

        foreach ($items as $item) 
        {
            $new_item = $this->filter_item($item, $user, $parentFolder);
            if( !empty($new_item))
            {
                $itemsArray[] = $new_item; 
            }       
        }
        
        return $itemsArray;
    }

    public function filter_item($item, $user, $parentFolder)
    {
        $filtered_item = array(
            'assetID'                      => $item->assetid,
            'assetType'                    => is_null($item->assettype) ? 0 : $item->assettype,
            'inventoryName'                => is_null($item->inventoryname) ? 'Unknow Name' : $item->inventoryname,
            'inventoryDescription'         => is_null($item->description) ? '' : $item->description,
            'inventoryNextPermissions'     => \Opensim\Permission::PERM_ALL,
            'inventoryCurrentPermissions'  => \Opensim\Permission::PERM_ALL,
            'invType'                      => is_null($item->invtype) ? 0 : $item->invtype,
            'creatorID'                    => $user->uuid,
            'inventoryBasePermissions'     => \Opensim\Permission::PERM_ALL,
            'inventoryEveryOnePermissions' => \Opensim\Permission::PERM_ALL,
            'salePrice'                    => is_null($item->saleprice) ? 0 : $item->saleprice,
            'saleType'                     => is_null($item->saletype) ? 0 : $item->saletype,
            'creationDate'                 => time(),
            'groupID'                      => $item->groupid,
            'groupOwned'                   => $item->groupowned,
            'flags'                        => $item->flags,
            'inventoryID'                  => UUID::random(),
            'avatarID'                     => $user->uuid,
            'parentFolderID'               => $parentFolder,
            'inventoryGroupPermissions'    => \Opensim\Permission::PERM_ALL,
        );

        return $filtered_item;
    }

    public function load_custom_appearance($appearanceData, $user)
    {
        $assetsIds       = array();
        $appearanceArray = array();
        foreach ($appearanceData as $data) 
        {
            $new_data = array(
                'PrincipalID' => $user->uuid,
                'Name'        => $data->name,
                'Value'       => $data->value,
            );

            // Update the UserID to 
            // the current user uuid
            if($data->name == 'UserID')
            {
                $new_data['Value'] = $user->uuid;
            }
            
            // This is an attachment
            // normaly the attachments
            // hold the inventoryID instead
            // of assetID. In our case when
            // we save the appearance in the
            // backend it automatically chage
            // the attachment value to its assetID
            // so make it ease for us here
            // values for this field should be
            // _ap_nn => uuid
            // Opensim Version 0.7.5
            if($this->check_attachment($data->name))
            {
                if(\Opensim\UUID::is_valid($data->value))
                {
                    $assetsIds[$data->name] = $data->value;
                }
            }

            // This is a wearable
            // parse values and add
            // to assetsIds list. The
            // values for this field should be
            // Wearable n:n => inventoryid:assetid
            // Opensim Version 0.7.5
            if($this->check_wearable($data->name))
            {
                $itemId = explode(':', $data->value);
                if(count($itemId) == 2)
                {
                    if(\Opensim\UUID::is_valid($itemId[1]))
                    {
                        $assetsIds[$data->name] = $itemId[1];
                    }
                }
            }
            
            $appearanceArray[$data->name] = $new_data;
        }

        if( !empty($assetsIds))
        {
            // Get user new items from the database
            $newItems = $this->inventoryItem->where_in('assetID', array_values($assetsIds))->where('avatarID', '=', $user->uuid)->get();
        }

        // If we dont find any items in the
        // user inventory with those asset ids
        // for some reason the items were not
        // created in the new user account inventory
        // lets remove it from the appareance
        // since it won't work anyway
        if( !isset($newItems) or empty($newItems))
        {
            foreach ($assetsIds as $a_item) 
            {
                $key = $this->recursive_array_search($a_item, $appearanceArray);
                
                if(isset($key) and !empty($key))
                {
                    unset($appearanceArray[$key]);
                }
            }
        }
        else
        {
            // We have assets in the new user inventory.
            // Iterate the items to update the 
            // values to the new inventoryid
            foreach ($newItems as $item) 
            {
                // check if the item assetID is in the 
                // assetsIds list
                $key = array_search($item->assetid, $assetsIds);
                if(isset($key))
                {
                    // Check for Wearable
                    // Wearable n:n => inventoryid:assetid
                    // Opensim Version 0.7.5
                    if($this->check_wearable($key))
                    {
                        if(isset($appearanceArray[$key]))
                        {
                            $appearanceArray[$key]['Value'] = $item->inventoryid.':'.$assetsIds[$key];
                        }
                    }

                    // Check for Attachment
                    // _ap_nn => uuid
                    // Opensim Version 0.7.5
                    if($this->check_attachment($key))
                    {
                        if(isset($appearanceArray[$key]))
                        {
                            $appearanceArray[$key]['Value'] = $item->inventoryid;
                        }
                    }
                }
            }
        }

        return $appearanceArray;
    }

    private function recursive_array_search($needle, $haystack) 
    {
        foreach($haystack as $key => $value) 
        {
            $current_key = $key;
            if($needle === $value OR (is_array($value) && $this->recursive_array_search($needle, $value))) 
            {
                return $current_key;
            }
        }
        return false;
    }

    private function check_wearable($value)
    {
        return preg_match('/Wearable\s\d+\:\d+/', $value);
    }

    private function check_attachment($value)
    {
        return preg_match('/_ap_\d+/', $value);
    }
}