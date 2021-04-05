<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tab extends Model
{
    use HasFactory;

    protected $table = 'tabs';
    protected $primaryKey = 'id';

    public function noteInfo() {
        return $this->hasOne('App\Models\Note', 'tab_id', 'id');
    }
}
