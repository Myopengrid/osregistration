<?php namespace Osregistration\Model;

use \Eloquent;

class AvatarItem extends Eloquent {

    public static $table = 'osregistration_avatar_items';

    public function avatar()
    {
        return $this->belongs_to('Osregistration\Models\Avatar');
    }
}