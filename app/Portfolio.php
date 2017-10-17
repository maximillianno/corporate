<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    //
    public function filter() {
        return $this->belongsTo('App\Filter','filter_alias', 'alias');
    }
}
