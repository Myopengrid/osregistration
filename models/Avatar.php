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

    public function image_url()
    {
        return \URL::base().'/'.$this->image_path.$this->image_full_name;
    }

    public function image_path()
    {
        return $this->image_path.$this->image_full_name;
    }

    public static function active()
    {
        return self::where_status(1)->order_by('order', 'asc')->order_by('created_at', 'desc');
    }
}