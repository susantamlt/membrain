<?php
namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Domainblacklist extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'domain_blacklist';
    public static function validator($input){
        $rules = array(
            'domain'=>'required|string|max:255|unique:domain_blacklist',
            'comment'=>'required|string|max:255',
        );
        return Validator::make($input,$rules);
    }
    public $timestamps = false;
    protected $fillable = array(
        'domain',
        'comment'
    );
}