<?php namespace Osregistration\Model;

use \Eloquent;

class AvatarAppearance extends Eloquent {

    public static $table = 'osregistration_avatar_appearances';

    public function avatar()
    {
        return $this->belongs_to('Osregistration\Models\Avatar');
    }
}