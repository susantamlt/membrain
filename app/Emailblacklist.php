<?php
namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Emailblacklist extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'email_blacklist';
    public static function validator($input){
        $rules = array(
            'email_address'=>'required|string|email|max:255|unique:email_blacklist',
            'comment'=>'required|string|max:255',
        );
        return Validator::make($input,$rules);
    }
    public $timestamps = false;
    protected $fillable = array(
        'email_address',
        'comment'
    );
}
