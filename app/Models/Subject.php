<?php

namespace App\Models;

class Subject extends Item
{
    protected $table = 'items';

    public function items()
    {
        return $this->morphMany(Item::class, 'refer');
    }

}
