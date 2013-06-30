<?php namespace Osregistration\Model;

use \Eloquent;

class Avatar extends Eloquent {

    public static $table = 'osregistration_avatar';

    public function appearance()
    {
        return $this->has_many('Osregistration\Model\AvatarAppearance');
    }

    public function items()
    {
        return $this->has_many('Osregistration\Model\AvatarItem');
    }

    public static function active()
    {
        return self::where_status(1)->order_by('order', 'asc')->order_by('updated_at', 'desc');
    }
}