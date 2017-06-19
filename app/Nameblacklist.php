<?php
namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Nameblacklist extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'name_blacklist';
    public static function validator($input){
        $rules = array(
            'name'=>'required|string|max:255|unique:name_blacklist',
            'comment'=>'required|string|max:255',
        );
        return Validator::make($input,$rules);
    }
    public $timestamps = false;
    protected $fillable = array(
        'name',
        'comment'
    );
}
