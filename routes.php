<?php

require_once 'routes/osregistration.php';

require_once 'routes/avatars.php';

require_once 'routes/frontend/registration.php';

IoC::register('customavatar', function($avatarData = null) {
    
    if(is_null($avatarData))
    {
        $avatarData = new \Osregistration\Model\AvatarAppearance;
    }
    $inventoryItem = new \Opensim\Model\Os\InventoryItem;
    //$avatar        = new \Opensim\Model\Os\Avatar;
    return new Osregistration\CustomAvatar($inventoryItem);
});

Event::listen('opensim.model.os.inventoryfolder.create_inventory', function ($inventory_uuid, $inventory_folders)
{
    $user_uuid = $inventory_folders['Textures']['agentID'];
    
    $custom_avatar_folder = array(
        'Starting Appearance' => array(
            'folderName'      => 'Starting Appearance',
            'type'            => -1,
            'version'         => 1,
            'folderID'        => Opensim\UUID::random(),
            'agentID'         => $user_uuid,
            'parentFolderID'  => $inventory_uuid,
        )
    );

    return array_merge($inventory_folders, $custom_avatar_folder);
});

Event::listen('opensim.load.avatar.items', function($avatar_id, $user, $account_inventory)
{
    $avatarItems = Osregistration\Model\AvatarItem::where_avatar_id($avatar_id)->get();
    if(isset($avatarItems) and !empty($avatarItems))
    {
        return IoC::resolve('customavatar', $avatarItems)->load_custom_items($avatarItems, $user, $account_inventory);
    }

    return null;
});

Event::listen('opensim.load.avatar.appearance', function($avatar_id, $user)
{
    $avatarAppearance = Osregistration\Model\AvatarAppearance::where_avatar_id($avatar_id)->get();
    if(isset($avatarAppearance) and !empty($avatarAppearance))
    {
        return IoC::resolve('customavatar', $avatarAppearance)->load_custom_appearance($avatarAppearance, $user);
    }

    return null;
});