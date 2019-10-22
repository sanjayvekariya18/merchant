<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activities extends Model
{
    protected $table = 'activities';

    protected $primaryKey = 'activity_id';

    public $timestamps = false;
}
