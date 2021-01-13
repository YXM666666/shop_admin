<?php


namespace app\index\model;


use think\Model;

class Profile extends Model
{
public function profile(){
    return $this->belongsTo('tp_user');
}
}