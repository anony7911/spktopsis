<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class rtlh extends Model
{
    use SoftDeletes;

    protected $table = 'rtlhs';

    protected $dates = ['deleted_at'];
}
