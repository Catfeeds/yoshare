<?php

namespace App\Models;

class VoteItem extends Item
{
    protected $table = 'items';

    public function getCountAttribute(){
        return $this->integer1;
    }

    public function setCountAttribute($count){
        $this->attributes['integer1'] = $count;
    }
}
