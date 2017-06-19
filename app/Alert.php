<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
   	protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'alerts';

    public $timestamps = false;
    protected $fillable = array(
        'subject',
        'body',
        'filename',
        'acknowledged',
        'created'
    );
}
