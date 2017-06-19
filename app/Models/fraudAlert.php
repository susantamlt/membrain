<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class fraudAlert extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'alerts';    
    public $timestamps = false;
}